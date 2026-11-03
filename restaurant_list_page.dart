import 'package:flutter/material.dart';
import '../services/api_service.dart';
class RestaurantListPage extends StatefulWidget {
  const RestaurantListPage({super.key});
  @override State<RestaurantListPage> createState()=>_RestaurantListPageState();
}
class _RestaurantListPageState extends State<RestaurantListPage>{
  final api=ApiService(); late Future<List<dynamic>> data;
  @override void initState(){super.initState();data=api.restaurants();}
  @override Widget build(BuildContext context)=>Scaffold(
    appBar:AppBar(title:const Text('SwiftBite restaurants')),
    body:FutureBuilder<List<dynamic>>(future:data,builder:(context,s){
      if(s.connectionState==ConnectionState.waiting)return const Center(child:CircularProgressIndicator());
      if(s.hasError)return Center(child:Text('API error: ${s.error}'));
      final rows=s.data??[];
      if(rows.isEmpty)return const Center(child:Text('No restaurants listed yet.'));
      return ListView.builder(itemCount:rows.length,itemBuilder:(context,i){
        final r=rows[i] as Map<String,dynamic>;
        return ListTile(leading:const CircleAvatar(child:Icon(Icons.restaurant)),
          title:Text(r['name']??''),subtitle:Text(r['description']??''));
      });
    }),
  );
}
