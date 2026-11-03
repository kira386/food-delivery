import 'package:flutter/material.dart';
import 'screens/restaurant_list_page.dart';
void main() => runApp(const SwiftBiteApp());
class SwiftBiteApp extends StatelessWidget {
  const SwiftBiteApp({super.key});
  @override Widget build(BuildContext context) => MaterialApp(
    title: 'SwiftBite', debugShowCheckedModeBanner: false,
    theme: ThemeData(colorSchemeSeed: Colors.deepOrange, useMaterial3: true),
    home: const RestaurantListPage(),
  );
}
