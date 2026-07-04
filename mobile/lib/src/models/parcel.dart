import 'package:equatable/equatable.dart';

import 'culture.dart';

/// Mirrors App\Http\Resources\ParcelResource.
class Parcel extends Equatable {
  const Parcel({
    required this.id,
    required this.nom,
    this.surface,
    this.status,
    this.healthScore,
    this.datePlantation,
    this.dateRecolteEstimee,
    this.latitude,
    this.longitude,
    this.createdAt,
    this.culture,
  });

  final int id;
  final String nom;
  final double? surface;
  final String? status;
  final double? healthScore;
  final DateTime? datePlantation;
  final DateTime? dateRecolteEstimee;
  final double? latitude;
  final double? longitude;
  final DateTime? createdAt;
  final Culture? culture;

  factory Parcel.fromJson(Map<String, dynamic> json) {
    return Parcel(
      id: json['id'] as int,
      nom: json['nom'] as String? ?? '',
      surface: (json['surface'] as num?)?.toDouble(),
      status: json['status'] as String?,
      healthScore: (json['health_score'] as num?)?.toDouble(),
      datePlantation: _date(json['date_plantation']),
      dateRecolteEstimee: _date(json['date_recolte_estimee']),
      latitude: (json['latitude'] as num?)?.toDouble(),
      longitude: (json['longitude'] as num?)?.toDouble(),
      createdAt: _date(json['created_at']),
      culture: json['culture'] is Map<String, dynamic>
          ? Culture.fromJson(json['culture'] as Map<String, dynamic>)
          : null,
    );
  }

  static DateTime? _date(dynamic v) =>
      v == null ? null : DateTime.tryParse(v.toString());

  @override
  List<Object?> get props => [id, nom, status, healthScore];
}
