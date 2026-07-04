import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../parcels/parcel_repository.dart';
import 'chat_controller.dart';

/// AI assistant chat. Optionally scoped to a parcel (`?parcel=ID`) and can
/// resume a past conversation (`?conversation=UUID`).
class ChatScreen extends ConsumerStatefulWidget {
  const ChatScreen({super.key, this.parcelId, this.conversationId});

  final int? parcelId;
  final String? conversationId;

  @override
  ConsumerState<ChatScreen> createState() => _ChatScreenState();
}

class _ChatScreenState extends ConsumerState<ChatScreen> {
  final _input = TextEditingController();
  final _scroll = ScrollController();

  ({String? conversationId, int? parcelId}) get _args =>
      (conversationId: widget.conversationId, parcelId: widget.parcelId);

  @override
  void dispose() {
    _input.dispose();
    _scroll.dispose();
    super.dispose();
  }

  void _send() {
    final text = _input.text;
    if (text.trim().isEmpty) return;
    _input.clear();
    ref.read(chatControllerProvider(_args).notifier).send(text);
  }

  void _scrollToBottom() {
    WidgetsBinding.instance.addPostFrameCallback((_) {
      if (_scroll.hasClients) {
        _scroll.animateTo(
          _scroll.position.maxScrollExtent,
          duration: const Duration(milliseconds: 250),
          curve: Curves.easeOut,
        );
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(chatControllerProvider(_args));

    // Keep the newest message visible as the transcript grows.
    ref.listen(
      chatControllerProvider(_args).select((s) => s.messages.length),
      (_, __) => _scrollToBottom(),
    );

    return Scaffold(
      appBar: AppBar(
        title: const Text('Assistant'),
        actions: [
          if (state.messages.isNotEmpty)
            IconButton(
              tooltip: 'New conversation',
              icon: const Icon(Icons.add_comment_outlined),
              onPressed: () =>
                  ref.read(chatControllerProvider(_args).notifier).reset(),
            ),
          IconButton(
            tooltip: 'History',
            icon: const Icon(Icons.history),
            onPressed: () => context.push('/assistant/history'),
          ),
        ],
      ),
      body: Column(
        children: [
          if (widget.parcelId != null) _ParcelContextBanner(widget.parcelId!),
          Expanded(
            child: state.loadingConversation
                ? const Center(child: CircularProgressIndicator())
                : state.messages.isEmpty
                    ? _EmptyChat(onPrompt: (p) {
                        _input.text = p;
                        _send();
                      })
                    : ListView.builder(
                        controller: _scroll,
                        padding: const EdgeInsets.all(16),
                        itemCount:
                            state.messages.length + (state.sending ? 1 : 0),
                        itemBuilder: (context, i) {
                          if (i == state.messages.length) {
                            return const _TypingIndicator();
                          }
                          final m = state.messages[i];
                          return _Bubble(
                            message: m,
                            onRate: m.interactionId == null
                                ? null
                                : (up) => ref
                                    .read(chatControllerProvider(_args)
                                        .notifier)
                                    .rate(m.interactionId!, up),
                          );
                        },
                      ),
          ),
          if (state.error != null)
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Text(
                state.error!,
                style: TextStyle(color: Theme.of(context).colorScheme.error),
              ),
            ),
          _Composer(
            controller: _input,
            enabled: !state.sending,
            onSend: _send,
          ),
        ],
      ),
    );
  }
}

/// Shows which parcel the conversation is scoped to.
class _ParcelContextBanner extends ConsumerWidget {
  const _ParcelContextBanner(this.parcelId);
  final int parcelId;

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final parcel = ref.watch(parcelProvider(parcelId)).valueOrNull;
    final scheme = Theme.of(context).colorScheme;
    return Material(
      color: scheme.primaryContainer.withValues(alpha: 0.5),
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
        child: Row(
          children: [
            Icon(Icons.grid_view, size: 16, color: scheme.primary),
            const SizedBox(width: 8),
            Expanded(
              child: Text(
                'About parcel: ${parcel?.nom ?? '#$parcelId'}',
                style: TextStyle(
                  fontSize: 13,
                  fontWeight: FontWeight.w600,
                  color: scheme.onPrimaryContainer,
                ),
              ),
            ),
            IconButton(
              visualDensity: VisualDensity.compact,
              icon: const Icon(Icons.close, size: 16),
              onPressed: () => context.go('/assistant'),
            ),
          ],
        ),
      ),
    );
  }
}

class _EmptyChat extends StatelessWidget {
  const _EmptyChat({required this.onPrompt});
  final ValueChanged<String> onPrompt;

  static const _suggestions = [
    'Quand dois-je irriguer mes cultures cette semaine ?',
    'Comment améliorer la santé de mon sol ?',
    'Quels traitements contre le mildiou de la tomate ?',
    'Quel est le meilleur moment pour récolter le blé ?',
  ];

  @override
  Widget build(BuildContext context) {
    final scheme = Theme.of(context).colorScheme;
    return ListView(
      padding: const EdgeInsets.all(24),
      children: [
        const SizedBox(height: 24),
        Container(
          alignment: Alignment.center,
          child: Container(
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              color: scheme.primaryContainer.withValues(alpha: 0.5),
              shape: BoxShape.circle,
            ),
            child: Icon(Icons.smart_toy_outlined,
                size: 44, color: scheme.primary),
          ),
        ),
        const SizedBox(height: 16),
        Text(
          'Ask your farming assistant',
          textAlign: TextAlign.center,
          style: Theme.of(context).textTheme.titleLarge,
        ),
        const SizedBox(height: 8),
        Text(
          'Irrigation, diseases, treatments, harvest timing — '
          'get expert agronomic advice.',
          textAlign: TextAlign.center,
          style: Theme.of(context)
              .textTheme
              .bodyMedium
              ?.copyWith(color: Theme.of(context).hintColor),
        ),
        const SizedBox(height: 24),
        ..._suggestions.map(
          (s) => Padding(
            padding: const EdgeInsets.only(bottom: 8),
            child: OutlinedButton(
              onPressed: () => onPrompt(s),
              style: OutlinedButton.styleFrom(
                alignment: Alignment.centerLeft,
                padding:
                    const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
              ),
              child: Text(s, style: const TextStyle(fontSize: 13)),
            ),
          ),
        ),
      ],
    );
  }
}

class _Bubble extends StatelessWidget {
  const _Bubble({required this.message, this.onRate});

  final ChatMessage message;
  final ValueChanged<bool>? onRate;

  @override
  Widget build(BuildContext context) {
    final scheme = Theme.of(context).colorScheme;
    final isUser = message.role == 'user';

    final bubble = Container(
      constraints: BoxConstraints(
        maxWidth: MediaQuery.of(context).size.width * 0.78,
      ),
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
      decoration: BoxDecoration(
        color: message.failed
            ? scheme.errorContainer
            : isUser
                ? scheme.primary
                : scheme.surfaceContainerHighest,
        borderRadius: BorderRadius.only(
          topLeft: const Radius.circular(16),
          topRight: const Radius.circular(16),
          bottomLeft: Radius.circular(isUser ? 16 : 4),
          bottomRight: Radius.circular(isUser ? 4 : 16),
        ),
      ),
      child: Text(
        message.text,
        style: TextStyle(
          fontSize: 14.5,
          height: 1.4,
          color: message.failed
              ? scheme.onErrorContainer
              : isUser
                  ? scheme.onPrimary
                  : scheme.onSurface,
        ),
      ),
    );

    return Padding(
      padding: const EdgeInsets.only(bottom: 10),
      child: Column(
        crossAxisAlignment:
            isUser ? CrossAxisAlignment.end : CrossAxisAlignment.start,
        children: [
          Align(
            alignment: isUser ? Alignment.centerRight : Alignment.centerLeft,
            child: bubble,
          ),
          if (!isUser && !message.failed && onRate != null)
            Padding(
              padding: const EdgeInsets.only(top: 2),
              child: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  _RateButton(
                    icon: Icons.thumb_up_outlined,
                    activeIcon: Icons.thumb_up,
                    active: (message.feedbackRating ?? 0) >= 4,
                    onTap: () => onRate!(true),
                  ),
                  _RateButton(
                    icon: Icons.thumb_down_outlined,
                    activeIcon: Icons.thumb_down,
                    active: message.feedbackRating != null &&
                        message.feedbackRating! <= 2,
                    onTap: () => onRate!(false),
                  ),
                ],
              ),
            ),
        ],
      ),
    );
  }
}

class _RateButton extends StatelessWidget {
  const _RateButton({
    required this.icon,
    required this.activeIcon,
    required this.active,
    required this.onTap,
  });

  final IconData icon;
  final IconData activeIcon;
  final bool active;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    final scheme = Theme.of(context).colorScheme;
    return IconButton(
      visualDensity: VisualDensity.compact,
      iconSize: 16,
      icon: Icon(
        active ? activeIcon : icon,
        color: active ? scheme.primary : Theme.of(context).hintColor,
      ),
      onPressed: onTap,
    );
  }
}

/// Three pulsing dots while the assistant is thinking.
class _TypingIndicator extends StatefulWidget {
  const _TypingIndicator();

  @override
  State<_TypingIndicator> createState() => _TypingIndicatorState();
}

class _TypingIndicatorState extends State<_TypingIndicator>
    with SingleTickerProviderStateMixin {
  late final AnimationController _controller = AnimationController(
    vsync: this,
    duration: const Duration(milliseconds: 900),
  )..repeat();

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final scheme = Theme.of(context).colorScheme;
    return Align(
      alignment: Alignment.centerLeft,
      child: Container(
        margin: const EdgeInsets.only(bottom: 10),
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
        decoration: BoxDecoration(
          color: scheme.surfaceContainerHighest,
          borderRadius: BorderRadius.circular(16),
        ),
        child: AnimatedBuilder(
          animation: _controller,
          builder: (context, _) => Row(
            mainAxisSize: MainAxisSize.min,
            children: List.generate(3, (i) {
              final t = (_controller.value * 3 - i).clamp(0.0, 1.0);
              final scale = 0.6 + 0.4 * (1 - (t - 0.5).abs() * 2).clamp(0, 1);
              return Padding(
                padding: const EdgeInsets.symmetric(horizontal: 2),
                child: Transform.scale(
                  scale: scale.toDouble(),
                  child: CircleAvatar(
                    radius: 3.5,
                    backgroundColor: scheme.primary,
                  ),
                ),
              );
            }),
          ),
        ),
      ),
    );
  }
}

class _Composer extends StatelessWidget {
  const _Composer({
    required this.controller,
    required this.enabled,
    required this.onSend,
  });

  final TextEditingController controller;
  final bool enabled;
  final VoidCallback onSend;

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Padding(
        padding: const EdgeInsets.fromLTRB(16, 8, 16, 12),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.end,
          children: [
            Expanded(
              child: TextField(
                controller: controller,
                minLines: 1,
                maxLines: 4,
                textInputAction: TextInputAction.send,
                onSubmitted: (_) => enabled ? onSend() : null,
                decoration: const InputDecoration(
                  hintText: 'Ask about your crops…',
                ),
              ),
            ),
            const SizedBox(width: 8),
            IconButton.filled(
              onPressed: enabled ? onSend : null,
              icon: const Icon(Icons.send_rounded),
              style: IconButton.styleFrom(
                minimumSize: const Size(48, 48),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
