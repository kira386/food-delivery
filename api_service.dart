import 'dart:convert';
import 'package:http/http.dart' as http;

class ApiService {
  // Android emulator -> host machine. Change for physical device / production.
  static const baseUrl = 'http://10.0.2.2/swiftbite/public/api';
  Future<List<dynamic>> restaurants() async {
    final r = await http.get(Uri.parse('$baseUrl/restaurants.php'));
    if (r.statusCode != 200) throw Exception('Could not load restaurants');
    return (jsonDecode(r.body)['restaurants'] as List);
  }
}
