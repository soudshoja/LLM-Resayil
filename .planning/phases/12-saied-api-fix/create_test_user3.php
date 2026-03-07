$u = App\Models\User::firstOrCreate(['email' => 'perftest@llm.resayil.io'], ['name' => 'Perf Test', 'password' => bcrypt('PerfTest2026!'), 'email_verified_at' => now()]);
echo 'Done id=' . $u->id;
