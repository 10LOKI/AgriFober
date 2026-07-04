import '../core/json.dart';
import 'package:equatable/equatable.dart';

/// Mirrors App\Http\Resources\RecommendationResource: a product recommended
/// for a parcel's culture, with pivot-level dosage/notes when present.
class Recommendation extends Equatable {
  const Recommendation({
    required this.id,
    required this.nomCommercial,
    this.type,
    this.description,
    this.composantActif,
    this.dosageRecommande,
    this.delaiAvantRecolte,
    this.safetyInstructions,
    this.dosageSpecifique,
    this.notes,
  });

  final int id;
  final String nomCommercial;
  final String? type;
  final String? description;
  final String? composantActif;
  final String? dosageRecommande;
  final int? delaiAvantRecolte;
  final String? safetyInstructions;
  final String? dosageSpecifique;
  final String? notes;

  factory Recommendation.fromJson(Map<String, dynamic> json) {
    return Recommendation(
      id: asInt(json['id'])!,
      nomCommercial: json['nom_commercial'] as String? ?? '',
      type: json['type'] as String?,
      description: json['description'] as String?,
      composantActif: json['composant_actif'] as String?,
      dosageRecommande: json['dosage_recommande'] as String?,
      delaiAvantRecolte: asInt(json['delai_avant_recolte']),
      safetyInstructions: json['safety_instructions'] as String?,
      dosageSpecifique: json['dosage_specifique'] as String?,
      notes: json['notes'] as String?,
    );
  }

  @override
  List<Object?> get props => [id, nomCommercial];
}
