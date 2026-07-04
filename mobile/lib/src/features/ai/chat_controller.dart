import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/api_exception.dart';
import 'ai_repository.dart';

/// One bubble in the chat transcript.
class ChatMessage {
  const ChatMessage({
    required this.role,
    required this.text,
    this.interactionId,
    this.feedbackRating,
    this.failed = false,
  });

  final String role; // user | assistant
  final String text;

  /// Backend id of the exchange this assistant message belongs to
  /// (used for feedback). Null for user bubbles and failed turns.
  final int? interactionId;
  final int? feedbackRating;
  final bool failed;

  ChatMessage copyWith({int? feedbackRating}) => ChatMessage(
        role: role,
        text: text,
        interactionId: interactionId,
        feedbackRating: feedbackRating ?? this.feedbackRating,
        failed: failed,
      );
}

class ChatState {
  const ChatState({
    this.messages = const [],
    this.conversationId,
    this.sending = false,
    this.loadingConversation = false,
    this.error,
  });

  final List<ChatMessage> messages;
  final String? conversationId;
  final bool sending;
  final bool loadingConversation;
  final String? error;

  ChatState copyWith({
    List<ChatMessage>? messages,
    String? conversationId,
    bool? sending,
    bool? loadingConversation,
    String? error,
    bool clearError = false,
  }) =>
      ChatState(
        messages: messages ?? this.messages,
        conversationId: conversationId ?? this.conversationId,
        sending: sending ?? this.sending,
        loadingConversation: loadingConversation ?? this.loadingConversation,
        error: clearError ? null : (error ?? this.error),
      );
}

/// One controller per (conversationId, parcelId) pair so opening a past
/// conversation or a parcel-scoped chat gets its own transcript.
final chatControllerProvider = StateNotifierProvider.autoDispose
    .family<ChatController, ChatState, ({String? conversationId, int? parcelId})>(
  (ref, args) {
    final controller =
        ChatController(ref.watch(aiRepositoryProvider), parcelId: args.parcelId);
    if (args.conversationId != null) {
      controller.loadConversation(args.conversationId!);
    }
    return controller;
  },
);

class ChatController extends StateNotifier<ChatState> {
  ChatController(this._repo, {this.parcelId}) : super(const ChatState());

  final AiRepository _repo;
  final int? parcelId;

  /// Rebuild the transcript of an existing conversation from the backend log.
  Future<void> loadConversation(String conversationId) async {
    state = state.copyWith(
      loadingConversation: true,
      conversationId: conversationId,
      clearError: true,
    );
    try {
      final log = await _repo.conversation(conversationId);
      final messages = <ChatMessage>[];
      for (final i in log) {
        messages.add(ChatMessage(role: 'user', text: i.promptText));
        if (i.responseText != null) {
          messages.add(ChatMessage(
            role: 'assistant',
            text: i.responseText!,
            interactionId: i.id,
            feedbackRating: i.feedbackRating,
          ));
        }
      }
      state = state.copyWith(messages: messages, loadingConversation: false);
    } on ApiException catch (e) {
      state = state.copyWith(loadingConversation: false, error: e.message);
    } catch (_) {
      state = state.copyWith(
        loadingConversation: false,
        error: 'Could not load the conversation.',
      );
    }
  }

  Future<void> send(String text) async {
    final message = text.trim();
    if (message.isEmpty || state.sending) return;

    state = state.copyWith(
      messages: [...state.messages, ChatMessage(role: 'user', text: message)],
      sending: true,
      clearError: true,
    );

    try {
      final reply = await _repo.chat(
        message: message,
        conversationId: state.conversationId,
        parcelId: parcelId,
      );
      state = state.copyWith(
        conversationId: reply.conversationId,
        messages: [
          ...state.messages,
          ChatMessage(
            role: 'assistant',
            text: reply.reply,
            interactionId: reply.interaction?.id,
          ),
        ],
        sending: false,
      );
    } on ApiException catch (e) {
      _failLastTurn(e.message);
    } catch (_) {
      _failLastTurn('The assistant is unreachable. Try again.');
    }
  }

  void _failLastTurn(String message) {
    state = state.copyWith(
      messages: [
        ...state.messages,
        ChatMessage(role: 'assistant', text: message, failed: true),
      ],
      sending: false,
    );
  }

  /// Thumbs feedback on an assistant reply: up -> 5, down -> 1.
  Future<void> rate(int interactionId, bool up) async {
    final rating = up ? 5 : 1;
    final updated = state.messages
        .map((m) => m.interactionId == interactionId
            ? m.copyWith(feedbackRating: rating)
            : m)
        .toList();
    state = state.copyWith(messages: updated);
    try {
      await _repo.sendFeedback(interactionId, rating);
    } catch (_) {
      // Feedback is best-effort; keep the optimistic UI.
    }
  }

  /// Start a fresh thread, keeping the parcel scope.
  void reset() => state = const ChatState();
}
