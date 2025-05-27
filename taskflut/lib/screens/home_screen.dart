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
        backgroundColor: Colors.red,
        duration: const Duration(seconds: 3),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: CustomAppBar(
        title: 'TaskFlut',
        actions: [
          _isProcessing
              ? const Padding(
                  padding: EdgeInsets.all(8.0),
                  child: CircularProgressIndicator(color: Colors.white),
                )
              : IconButton(
                  icon: const Icon(Icons.logout),
                  onPressed: _logout,
                ),
        ],
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _tasks.isEmpty
              ? Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const Text(
                        'No tasks yet. Add one!',
                        style: TextStyle(fontSize: 18),
                      ),
                      const SizedBox(height: 16),
                      ElevatedButton(
                        onPressed: _loadTasks,
                        child: const Text('Refresh'),
                      ),
                    ],
                  ),
                )
              : RefreshIndicator(
                  onRefresh: _loadTasks,
                  child: ListView.builder(
                    padding: const EdgeInsets.all(16),
                    itemCount: _tasks.length,
                    itemBuilder: (context, index) {
                      final task = _tasks[index];
                      return TaskItem(
                        key: ValueKey(task.id), // Important for animations
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
      floatingActionButton: _isProcessing
          ? FloatingActionButton(
              onPressed: null,
              backgroundColor: Colors.red[300],
              child: const CircularProgressIndicator(color: Colors.white),
            )
          : FloatingActionButton(
              backgroundColor: Colors.red,
              child: const Icon(Icons.add, color: Colors.white),
              onPressed: () async {
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
        content: const Text('Are you sure you want to delete this task?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('Cancel'),
          ),
          TextButton(
            onPressed: () => Navigator.pop(context, true),
            child: const Text(
              'Delete',
              style: TextStyle(color: Colors.red),
            ),
          ),
        ],
      ),
    );

    if (confirmed == true) {
      await _deleteTask(taskId);
    }
  }
}