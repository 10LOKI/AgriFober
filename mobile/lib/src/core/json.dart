/// Tolerant JSON number parsing.
///
/// Laravel casts decimal columns to strings (e.g. surface `"2.2000"`),
/// so numeric fields can arrive as num OR String depending on the column
/// type. These helpers accept both.
double? asDouble(dynamic v) {
  if (v == null) return null;
  if (v is num) return v.toDouble();
  return double.tryParse(v.toString());
}

int? asInt(dynamic v) {
  if (v == null) return null;
  if (v is num) return v.toInt();
  return int.tryParse(v.toString());
}
