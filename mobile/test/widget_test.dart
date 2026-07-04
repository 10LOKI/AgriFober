import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';

import 'package:agrifober_mobile/src/widgets/async_view.dart';
import 'package:agrifober_mobile/src/widgets/status_chip.dart';

void main() {
  testWidgets('StatusChip renders known parcel statuses',
      (WidgetTester tester) async {
    await tester.pumpWidget(
      const MaterialApp(
        home: Scaffold(
          body: Column(
            children: [
              StatusChip('grow'),
              StatusChip('harvest'),
              StatusChip('fallow'),
              StatusChip(null), // renders nothing, must not crash
            ],
          ),
        ),
      ),
    );

    expect(find.text('Growing'), findsOneWidget);
    expect(find.text('Harvest'), findsOneWidget);
    expect(find.text('Fallow'), findsOneWidget);
  });

  testWidgets('ErrorView retry callback fires', (WidgetTester tester) async {
    var retried = false;
    await tester.pumpWidget(
      MaterialApp(
        home: Scaffold(
          body: ErrorView(
            message: 'Network down',
            onRetry: () => retried = true,
          ),
        ),
      ),
    );

    expect(find.text('Network down'), findsOneWidget);
    await tester.tap(find.text('Try again'));
    expect(retried, isTrue);
  });

  testWidgets('EmptyView shows action button', (WidgetTester tester) async {
    var tapped = false;
    await tester.pumpWidget(
      MaterialApp(
        home: Scaffold(
          body: EmptyView(
            icon: Icons.grid_view,
            title: 'No parcels yet',
            actionLabel: 'Add parcel',
            onAction: () => tapped = true,
          ),
        ),
      ),
    );

    expect(find.text('No parcels yet'), findsOneWidget);
    await tester.tap(find.text('Add parcel'));
    expect(tapped, isTrue);
  });
}
