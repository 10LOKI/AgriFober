import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../models/product.dart';
import 'catalogue_repository.dart';

class ProductDetailScreen extends ConsumerWidget {
  const ProductDetailScreen({super.key, required this.productId});
  final int productId;

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final async = ref.watch(productDetailProvider(productId));

    return Scaffold(
      appBar: AppBar(
        title: Text(async.valueOrNull?.nomCommercial ?? 'Product'),
      ),
      body: async.when(
        loading: () => const Center(child: CircularProgressIndicator()),
        error: (e, _) => Center(
          child: Padding(
            padding: const EdgeInsets.all(24),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Text('$e', textAlign: TextAlign.center),
                const SizedBox(height: 12),
                FilledButton(
                  onPressed: () =>
                      ref.invalidate(productDetailProvider(productId)),
                  child: const Text('Retry'),
                ),
              ],
            ),
          ),
        ),
        data: (p) => _ProductDetail(product: p),
      ),
    );
  }
}

class _ProductDetail extends StatelessWidget {
  const _ProductDetail({required this.product});
  final Product product;

  @override
  Widget build(BuildContext context) {
    final p = product;
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        if (p.type != null)
          Align(
            alignment: Alignment.centerLeft,
            child: Chip(label: Text(p.type!)),
          ),
        if (p.description != null && p.description!.isNotEmpty) ...[
          const SizedBox(height: 12),
          Text(p.description!),
        ],
        const SizedBox(height: 16),
        Card(
          child: Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text('Details',
                    style: Theme.of(context).textTheme.titleMedium),
                const SizedBox(height: 12),
                if (p.composantActif != null)
                  _InfoRow(
                    icon: Icons.science_outlined,
                    label: 'Active component',
                    value: p.composantActif!,
                  ),
                if (p.dosageRecommande != null)
                  _InfoRow(
                    icon: Icons.colorize_outlined,
                    label: 'Recommended dosage',
                    value: p.dosageRecommande!,
                  ),
                if (p.delaiAvantRecolte != null)
                  _InfoRow(
                    icon: Icons.schedule,
                    label: 'Pre-harvest interval',
                    value: '${p.delaiAvantRecolte} days',
                  ),
              ],
            ),
          ),
        ),
        if (p.usageMethod != null && p.usageMethod!.isNotEmpty) ...[
          const SizedBox(height: 16),
          _TextCard(title: 'How to use', body: p.usageMethod!),
        ],
        if (p.avantages != null && p.avantages!.isNotEmpty) ...[
          const SizedBox(height: 16),
          _TextCard(title: 'Benefits', body: p.avantages!),
        ],
        if (p.safetyInstructions != null &&
            p.safetyInstructions!.isNotEmpty) ...[
          const SizedBox(height: 16),
          Card(
            color: Theme.of(context).colorScheme.errorContainer,
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Icon(Icons.warning_amber_rounded,
                          color:
                              Theme.of(context).colorScheme.error),
                      const SizedBox(width: 8),
                      Text('Safety instructions',
                          style: Theme.of(context)
                              .textTheme
                              .titleMedium),
                    ],
                  ),
                  const SizedBox(height: 8),
                  Text(p.safetyInstructions!),
                ],
              ),
            ),
          ),
        ],
      ],
    );
  }
}

class _TextCard extends StatelessWidget {
  const _TextCard({required this.title, required this.body});
  final String title;
  final String body;

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(title, style: Theme.of(context).textTheme.titleMedium),
            const SizedBox(height: 8),
            Text(body),
          ],
        ),
      ),
    );
  }
}

class _InfoRow extends StatelessWidget {
  const _InfoRow({
    required this.icon,
    required this.label,
    required this.value,
  });
  final IconData icon;
  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 6),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, size: 20, color: Theme.of(context).colorScheme.primary),
          const SizedBox(width: 12),
          Expanded(child: Text(label)),
          Flexible(
            child: Text(
              value,
              textAlign: TextAlign.end,
              style: const TextStyle(fontWeight: FontWeight.w600),
            ),
          ),
        ],
      ),
    );
  }
}
