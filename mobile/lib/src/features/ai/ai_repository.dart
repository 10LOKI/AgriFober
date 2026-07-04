import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/api_client.dart';
import '../../core/providers.dart';
import '../../models/interaction.dart';

final aiRepositoryProvider = Provider<AiRepository>((ref) {
  return AiRepository(ref.watch(apiClientProvider));
});

/// Reply of one chat turn: server-assigned conversation id + assistant text.
class ChatReply {
  const ChatReply({
    required this.conversationId,
    required this.reply,
    this.interaction,
  });

  final String conversationId;
  final String reply;
  final Interaction? interaction;

  factory ChatReply.fromJson(Map<String, dynamic> json) => ChatReply(
        conversationId: json['conversation_id'] as String? ?? '',
        reply: json['reply'] as String? ?? '',
        interaction: json['interaction'] is Map<String, dynamic>
            ? Interaction.fromJson(json['interaction'] as Map<String, dynamic>)
            : null,
      );
}

/// Talks to the /ai/* endpoints in routes/api.php.
class AiRepository {
  AiRepository(this._api);
  final ApiClient _api;

  Future<ChatReply> chat({
    required String message,
    String? conversationId,
    int? parcelId,
  }) async {
    final data = await _api.post('/ai/chat', body: {
      'message': message,
      if (conversationId != null) 'conversation_id': conversationId,
      if (parcelId != null) 'parcel_id': parcelId,
    });
    return ChatReply.fromJson(data as Map<String, dynamic>);
  }

  /// Latest interactions across all conversations, newest first.
  Future<List<Interaction>> history() async {
    final data = await _api.get('/ai/history');
    return (data as List)
        .map((e) => Interaction.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  /// Full log of one conversation, oldest first, so a chat can be resumed.
  Future<List<Interaction>> conversation(String conversationId) async {
    final data = await _api.get(
      '/ai/conversations/$conversationId',
      query: {'per_page': 100},
    );
    return (data as List)
        .map((e) => Interaction.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<void> deleteInteraction(int id) => _api.delete('/ai/history/$id');

  Future<void> sendFeedback(int id, int rating) =>
      _api.post('/ai/history/$id/feedback', body: {'rating': rating});
}

/// History list for the AI history screen.
final aiHistoryProvider =
    FutureProvider.autoDispose<List<Interaction>>((ref) {
  return ref.watch(aiRepositoryProvider).history();
});
