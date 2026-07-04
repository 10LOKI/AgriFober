import 'package:equatable/equatable.dart';

import 'product.dart';

/// Mirrors App\Http\Resources\CultureResource.
class Culture extends Equatable {
  const Culture({
    required this.id,
    required this.nomCommun,
    this.nomScientifique,
    this.type,
    this.saison,
    this.region,
    this.soilType,
    this.phSolMin,
    this.phSolMax,
    this.tempMin,
    this.tempMax,
    this.besoinEauCycle,
    this.conseils,
    this.products = const [],
  });

  final int id;
  final String nomCommun;
  final String? nomScientifique;
  final String? type;
  final String? saison;
  final String? region;
  final String? soilType;
  final double? phSolMin;
  final double? phSolMax;
  final double? tempMin;
  final double? tempMax;
  final double? besoinEauCycle;
  final String? conseils;
  final List<Product> products;

  factory Culture.fromJson(Map<String, dynamic> json) {
    return Culture(
      id: json['id'] as int,
      nomCommun: json['nom_commun'] as String? ?? '',
      nomScientifique: json['nom_scientifique'] as String?,
      type: json['type'] as String?,
      saison: json['saison'] as String?,
      region: json['region'] as String?,
      soilType: json['soil_type'] as String?,
      phSolMin: (json['ph_sol_min'] as num?)?.toDouble(),
      phSolMax: (json['ph_sol_max'] as num?)?.toDouble(),
      tempMin: (json['temp_min'] as num?)?.toDouble(),
      tempMax: (json['temp_max'] as num?)?.toDouble(),
      besoinEauCycle: (json['besoin_eau_cycle'] as num?)?.toDouble(),
      conseils: json['conseils'] as String?,
      products: (json['products'] as List?)
              ?.map((e) => Product.fromJson(e as Map<String, dynamic>))
              .toList() ??
          const [],
    );
  }

  @override
  List<Object?> get props => [id, nomCommun, type, saison];
}
