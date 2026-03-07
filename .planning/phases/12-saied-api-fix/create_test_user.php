<?php
// Create test user for UI tests
$user = new App\Models\User();
$user->name = 'Perf Test';
$user->email = 'perftest@llm.resayil.io';
$user->password = bcrypt('PerfTest2026!');
$user->email_verified_at = now();
$user->save();
echo 'CREATED id=' . $user->id . "\n";
