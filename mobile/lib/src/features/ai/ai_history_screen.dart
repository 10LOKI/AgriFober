import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../core/api_exception.dart';
import '../../models/interaction.dart';
import '../../widgets/async_view.dart';
import '../../widgets/skeleton.dart';
import 'ai_repository.dart';

/// Past AI exchanges, grouped so one conversation shows once (latest turn).
/// Tapping resumes the conversation; swiping deletes the exchange.
class AiHistoryScreen extends ConsumerWidget {
  const AiHistoryScreen({super.key});

  Future<void> _delete(
      BuildContext context, WidgetRef ref, Interaction i) async {
    try {
      await ref.read(aiRepositoryProvider).deleteInteraction(i.id);
      ref.invalidate(aiHistoryProvider);
    } on ApiException catch (e) {
      if (context.mounted) {
        ScaffoldMessenger.of(context)
            .showSnackBar(SnackBar(content: Text(e.message)));
      }
    }
  }

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final async = ref.watch(aiHistoryProvider);

    return Scaffold(
      appBar: AppBar(title: const Text('Conversation history')),
      body: async.when(
        loading: () => const SkeletonList(count: 8),
        error: (e, _) => ErrorView(
          message: '$e',
          onRetry: () => ref.invalidate(aiHistoryProvider),
        ),
        data: (items) {
          // Newest exchange per conversation drives the list entry.
          final latestPerConversation = <String, Interaction>{};
          for (final i in items) {
            final key = i.conversationId ?? 'single-${i.id}';
            latestPerConversation.putIfAbsent(key, () => i);
          }
          final conversations = latestPerConversation.values.toList();

          if (conversations.isEmpty) {
            return EmptyView(
              icon: Icons.forum_outlined,
              title: 'No conversations yet',
              subtitle: 'Your chats with the assistant will appear here.',
              actionLabel: 'Start chatting',
              onAction: () => context.go('/assistant'),
            );
          }

          return RefreshIndicator(
            onRefresh: () async => ref.invalidate(aiHistoryProvider),
            child: ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: conversations.length,
              itemBuilder: (context, index) {
                final i = conversations[index];
                return Dismissible(
                  key: ValueKey('conv-${i.id}'),
                  direction: DismissDirection.endToStart,
                  background: Container(
                    alignment: Alignment.centerRight,
                    padding: const EdgeInsets.only(right: 24),
                    decoration: BoxDecoration(
                      color: Theme.of(context).colorScheme.errorContainer,
                      borderRadius: BorderRadius.circular(16),
                    ),
                    child: Icon(
                      Icons.delete_outline,
                      color: Theme.of(context).colorScheme.onErrorContainer,
                    ),
                  ),
                  onDismissed: (_) => _delete(context, ref, i),
                  child: Card(
                    child: ListTile(
                      leading: CircleAvatar(
                        backgroundColor: Theme.of(context)
                            .colorScheme
                            .primaryContainer,
                        child: Icon(
                          _typeIcon(i.type),
                          size: 20,
                          color: Theme.of(context).colorScheme.primary,
                        ),
                      ),
                      title: Text(
                        i.promptText,
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                        style: const TextStyle(fontWeight: FontWeight.w600),
                      ),
                      subtitle: Text(
                        [
                          if (i.parcelNom != null) i.parcelNom!,
                          if (i.createdAt != null) _relative(i.createdAt!),
                        ].join(' · '),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                      trailing: const Icon(Icons.chevron_right),
                      onTap: i.conversationId == null
                          ? null
                          : () => context.go(
                              '/assistant?conversation=${i.conversationId}'),
                    ),
                  ),
                );
              },
            ),
          );
        },
      ),
    );
  }

  static IconData _typeIcon(String? type) => switch (type) {
        'diagnostic' => Icons.troubleshoot,
        'analyse_image' => Icons.image_search,
        _ => Icons.chat_bubble_outline,
      };

  static String _relative(DateTime d) {
    final diff = DateTime.now().difference(d);
    if (diff.inMinutes < 1) return 'just now';
    if (diff.inHours < 1) return '${diff.inMinutes} min ago';
    if (diff.inDays < 1) return '${diff.inHours} h ago';
    if (diff.inDays < 30) return '${diff.inDays} d ago';
    return '${d.year}-${d.month.toString().padLeft(2, '0')}-${d.day.toString().padLeft(2, '0')}';
  }
}
