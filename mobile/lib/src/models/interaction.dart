import '../core/json.dart';
import 'package:equatable/equatable.dart';

/// Mirrors App\Http\Resources\InteractionIAResource: one user prompt and the
/// AI reply that answered it, inside a conversation thread.
class Interaction extends Equatable {
  const Interaction({
    required this.id,
    this.conversationId,
    this.type,
    this.inputMode,
    required this.promptText,
    this.responseText,
    this.imageUrl,
    this.tokensUsed,
    this.modelVersion,
    this.feedbackRating,
    this.createdAt,
    this.parcelId,
    this.parcelNom,
  });

  final int id;
  final String? conversationId;
  final String? type; // chat | diagnostic | analyse_image
  final String? inputMode;
  final String promptText;
  final String? responseText;
  final String? imageUrl;
  final int? tokensUsed;
  final String? modelVersion;
  final int? feedbackRating; // 1..5 (thumbs map: down=1, up=5)
  final DateTime? createdAt;
  final int? parcelId;
  final String? parcelNom;

  factory Interaction.fromJson(Map<String, dynamic> json) {
    final response = json['response_data'];
    final parcel = json['parcel'];
    return Interaction(
      id: asInt(json['id'])!,
      conversationId: json['conversation_id'] as String?,
      type: json['type'] as String?,
      inputMode: json['input_mode'] as String?,
      promptText: json['prompt_text'] as String? ?? '',
      responseText: response is Map ? response['text'] as String? : null,
      imageUrl: json['image_url'] as String?,
      tokensUsed: asInt(json['tokens_used']),
      modelVersion: json['model_version'] as String?,
      feedbackRating: asInt(json['feedback_rating']),
      createdAt: json['created_at'] != null
          ? DateTime.tryParse(json['created_at'].toString())
          : null,
      parcelId: parcel is Map ? asInt(parcel['id']) : null,
      parcelNom: parcel is Map ? parcel['nom'] as String? : null,
    );
  }

  @override
  List<Object?> get props => [id, conversationId, feedbackRating];
}
