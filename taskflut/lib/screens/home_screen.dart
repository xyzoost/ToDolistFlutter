import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:taskflut/api/api_service.dart';
import 'package:taskflut/api/auth_service.dart';
import 'package:taskflut/models/task.dart';
import 'package:taskflut/screens/tasks/add_task_screen.dart';
import 'package:taskflut/screens/tasks/task_detail_screen.dart';
import 'package:taskflut/widgets/custom_app_bar.dart';
import 'package:taskflut/widgets/task_item.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  List<Task> _tasks = [];
  bool _isLoading = true;
  bool _isProcessing = false;

  @override
  void initState() {
    super.initState();
    _loadTasks();
  }

  Future<void> _loadTasks() async {
    if (!mounted) return;
    setState(() => _isLoading = true);

    try {
      final tasks = await ApiService.getTasks();
      if (!mounted) return;
      setState(() => _tasks = tasks);
    } catch (e) {
      if (!mounted) return;
      _showErrorSnackbar('Failed to load tasks: ${e.toString()}');
    } finally {
      if (mounted) {
        setState(() => _isLoading = false);
      }
    }
  }

  Future<void> _addTask(Task task) async {
    setState(() => _isProcessing = true);
    try {
      final newTask = await ApiService.createTask(task);
      if (!mounted) return;
      setState(() => _tasks.insert(0, newTask));
    } catch (e) {
      _showErrorSnackbar('Failed to create task: ${e.toString()}');
    } finally {
      if (mounted) {
        setState(() => _isProcessing = false);
      }
    }
  }

  Future<void> _updateTask(Task task) async {
    setState(() => _isProcessing = true);
    try {
      final updatedTask = await ApiService.updateTask(task);
      if (!mounted) return;
      setState(() {
        final index = _tasks.indexWhere((t) => t.id == task.id);
        if (index != -1) _tasks[index] = updatedTask;
      });
    } catch (e) {
      _showErrorSnackbar('Failed to update task: ${e.toString()}');
    } finally {
      if (mounted) {
        setState(() => _isProcessing = false);
      }
    }
  }

  Future<void> _deleteTask(int taskId) async {
    setState(() => _isProcessing = true);
    try {
      await ApiService.deleteTask(taskId);
      if (!mounted) return;
      setState(() => _tasks.removeWhere((task) => task.id == taskId));
    } catch (e) {
      _showErrorSnackbar('Failed to delete task: ${e.toString()}');
    } finally {
      if (mounted) {
        setState(() => _isProcessing = false);
      }
    }
  }

  Future<void> _toggleTaskStatus(int taskId, bool completed) async {
    setState(() => _isProcessing = true);
    try {
      final updatedTask = await ApiService.toggleTaskStatus(taskId, completed);
      if (!mounted) return;
      setState(() {
        final index = _tasks.indexWhere((t) => t.id == taskId);
        if (index != -1) _tasks[index] = updatedTask;
      });
    } catch (e) {
      _showErrorSnackbar('Failed to update status: ${e.toString()}');
    } finally {
      if (mounted) {
        setState(() => _isProcessing = false);
      }
    }
  }

  Future<void> _logout() async {
    setState(() => _isProcessing = true);
    try {
      final authService = Provider.of<AuthService>(context, listen: false);
      await authService.logout();
      if (!mounted) return;
      Navigator.pushReplacementNamed(context, '/login');
    } catch (e) {
      _showErrorSnackbar('Logout failed: ${e.toString()}');
    } finally {
      if (mounted) {
        setState(() => _isProcessing = false);
      }
    }
  }

  void _showErrorSnackbar(String message) {
    if (!mounted) return;
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message),
        backgroundColor: Colors.red[400],
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
        duration: const Duration(seconds: 3),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final colorScheme = theme.colorScheme;

    return Scaffold(
      backgroundColor: Colors.grey[50],
      appBar: CustomAppBar(
        title: 'Task Manager',
        backgroundColor: colorScheme.primary,
        titleColor: Colors.white,
        actions: [
          _isProcessing
              ? const Padding(
                  padding: EdgeInsets.all(12.0),
                  child: SizedBox(
                    width: 20,
                    height: 20,
                    child: CircularProgressIndicator(
                      strokeWidth: 2,
                      color: Colors.white,
                    ),
                  ),
                )
              : IconButton(
                  icon: const Icon(Icons.logout, color: Colors.white),
                  onPressed: _logout,
                ),
        ],
      ),
      body: _isLoading
          ? Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const CircularProgressIndicator(),
                  const SizedBox(height: 16),
                  Text(
                    'Loading your tasks...',
                    style: theme.textTheme.bodyLarge?.copyWith(
                      color: Colors.grey[600],
                    ),
                  ),
                ],
              ),
            )
          : _tasks.isEmpty
          ? Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(Icons.task_outlined, size: 64, color: Colors.grey[400]),
                  const SizedBox(height: 16),
                  Text(
                    'No tasks yet',
                    style: theme.textTheme.headlineSmall?.copyWith(
                      color: Colors.grey[700],
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    'Tap the + button to add your first task',
                    style: theme.textTheme.bodyMedium?.copyWith(
                      color: Colors.grey[600],
                    ),
                  ),
                  const SizedBox(height: 24),
                  OutlinedButton(
                    style: OutlinedButton.styleFrom(
                      foregroundColor: colorScheme.primary,
                      side: BorderSide(color: colorScheme.primary),
                      padding: const EdgeInsets.symmetric(
                        horizontal: 24,
                        vertical: 12,
                      ),
                    ),
                    onPressed: _loadTasks,
                    child: const Text('Refresh'),
                  ),
                ],
              ),
            )
          : RefreshIndicator(
              color: colorScheme.primary,
              onRefresh: _loadTasks,
              child: ListView.separated(
                padding: const EdgeInsets.all(16),
                itemCount: _tasks.length,
                separatorBuilder: (context, index) => const SizedBox(height: 8),
                itemBuilder: (context, index) {
                  final task = _tasks[index];
                  return TaskItem(
                    key: ValueKey(task.id),
                    task: task,
                    onTap: () async {
                      final result = await Navigator.push<Task>(
                        context,
                        MaterialPageRoute(
                          builder: (_) => TaskDetailScreen(task: task),
                        ),
                      );
                      if (result != null) await _updateTask(result);
                    },
                    onDelete: () => _showDeleteDialog(task.id),
                    onToggle: (value) => _toggleTaskStatus(task.id, value),
                  );
                },
              ),
            ),
      floatingActionButton: FloatingActionButton(
        backgroundColor: colorScheme.primary,
        elevation: 4,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        child: _isProcessing
            ? const CircularProgressIndicator(color: Colors.white)
            : const Icon(Icons.add, color: Colors.white),
        onPressed: _isProcessing
            ? null
            : () async {
                final result = await Navigator.push<Task>(
                  context,
                  MaterialPageRoute(builder: (_) => const AddTaskScreen()),
                );
                if (result != null) await _addTask(result);
              },
      ),
    );
  }

  Future<void> _showDeleteDialog(int taskId) async {
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Delete Task'),
        content: const Text('This action cannot be undone.'),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: Text('Cancel', style: TextStyle(color: Colors.grey[600])),
          ),
          TextButton(
            onPressed: () => Navigator.pop(context, true),
            style: TextButton.styleFrom(foregroundColor: Colors.red),
            child: const Text('Delete'),
          ),
        ],
      ),
    );

    if (confirmed == true) {
      await _deleteTask(taskId);
    }
  }
}
