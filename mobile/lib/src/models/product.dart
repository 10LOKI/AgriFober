import 'package:equatable/equatable.dart';

/// Mirrors App\Http\Resources\ProductResource.
class Product extends Equatable {
  const Product({
    required this.id,
    required this.nomCommercial,
    this.description,
    this.composantActif,
    this.dosageRecommande,
    this.delaiAvantRecolte,
    this.type,
    this.avantages,
    this.usageMethod,
    this.safetyInstructions,
    this.image,
    this.pivotDosage,
    this.pivotNotes,
  });

  final int id;
  final String nomCommercial;
  final String? description;
  final String? composantActif;
  final String? dosageRecommande;
  final int? delaiAvantRecolte;
  final String? type;
  final String? avantages;
  final String? usageMethod;
  final String? safetyInstructions;
  final String? image;

  /// From the culture<->product pivot when present.
  final String? pivotDosage;
  final String? pivotNotes;

  factory Product.fromJson(Map<String, dynamic> json) {
    final pivot = json['pivot'] as Map<String, dynamic>?;
    return Product(
      id: json['id'] as int,
      nomCommercial: json['nom_commercial'] as String? ?? '',
      description: json['description'] as String?,
      composantActif: json['composant_actif'] as String?,
      dosageRecommande: json['dosage_recommande'] as String?,
      delaiAvantRecolte: (json['delai_avant_recolte'] as num?)?.toInt(),
      type: json['type'] as String?,
      avantages: json['avantages'] as String?,
      usageMethod: json['usage_method'] as String?,
      safetyInstructions: json['safety_instructions'] as String?,
      image: json['image'] as String?,
      pivotDosage: pivot?['dosage_specifique'] as String?,
      pivotNotes: pivot?['notes'] as String?,
    );
  }

  @override
  List<Object?> get props => [id, nomCommercial, type];
}
