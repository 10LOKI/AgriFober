import 'package:flutter/material.dart';

/// Parcel status metadata shared by list, detail and filters.
class ParcelStatusInfo {
  const ParcelStatusInfo(this.label, this.color, this.icon);
  final String label;
  final MaterialColor color;
  final IconData icon;
}

const parcelStatuses = <String, ParcelStatusInfo>{
  'grow': ParcelStatusInfo('Growing', Colors.green, Icons.trending_up),
  'harvest': ParcelStatusInfo('Harvest', Colors.orange, Icons.agriculture),
  'fallow': ParcelStatusInfo('Fallow', Colors.brown, Icons.grass),
};

class StatusChip extends StatelessWidget {
  const StatusChip(this.status, {super.key});
  final String? status;

  @override
  Widget build(BuildContext context) {
    if (status == null || status!.isEmpty) return const SizedBox.shrink();
    final info = parcelStatuses[status] ??
        ParcelStatusInfo(status!, Colors.grey, Icons.circle_outlined);
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
      decoration: BoxDecoration(
        color: isDark
            ? info.color.shade900.withValues(alpha: 0.4)
            : info.color.shade50,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(
          color: isDark ? info.color.shade700 : info.color.shade200,
        ),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(
            info.icon,
            size: 13,
            color: isDark ? info.color.shade300 : info.color.shade800,
          ),
          const SizedBox(width: 4),
          Text(
            info.label,
            style: TextStyle(
              fontSize: 12,
              fontWeight: FontWeight.w600,
              color: isDark ? info.color.shade300 : info.color.shade800,
            ),
          ),
        ],
      ),
    );
  }
}

/// Small circular health indicator (0-100) with score-driven color.
class HealthBadge extends StatelessWidget {
  const HealthBadge({super.key, required this.score, this.size = 44});

  final double? score;
  final double size;

  Color _color() {
    final s = score ?? 0;
    if (s >= 70) return Colors.green;
    if (s >= 40) return Colors.orange;
    return Colors.red;
  }

  @override
  Widget build(BuildContext context) {
    if (score == null) return const SizedBox.shrink();
    final color = _color();
    return SizedBox(
      width: size,
      height: size,
      child: Stack(
        alignment: Alignment.center,
        children: [
          SizedBox(
            width: size,
            height: size,
            child: CircularProgressIndicator(
              value: (score!.clamp(0, 100)) / 100,
              strokeWidth: 4,
              backgroundColor: color.withValues(alpha: 0.15),
              valueColor: AlwaysStoppedAnimation(color),
            ),
          ),
          Text(
            score!.toStringAsFixed(0),
            style: TextStyle(
              fontSize: size * 0.3,
              fontWeight: FontWeight.w700,
              color: color,
            ),
          ),
        ],
      ),
    );
  }
}
