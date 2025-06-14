import 'package:flutter/material.dart';

class CustomAppBar extends StatelessWidget implements PreferredSizeWidget {
  final String title;
  final Color? backgroundColor;
  final Color? titleColor;
  final List<Widget>? actions;

  const CustomAppBar({
    Key? key,
    required this.title,
    this.backgroundColor,
    this.titleColor,
    this.actions,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return AppBar(
      title: Text(
        title,
        style: TextStyle(
          color: titleColor ?? Colors.white,
          fontWeight: FontWeight.bold,
        ),
      ),
      backgroundColor: backgroundColor ?? Theme.of(context).primaryColor,
      elevation: 0,
      actions: actions,
      iconTheme: IconThemeData(color: titleColor ?? Colors.white),
    );
  }

  @override
  Size get preferredSize => const Size.fromHeight(kToolbarHeight);
}
