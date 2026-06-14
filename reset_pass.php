<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$u = App\Models\User::first();
if ($u) {
    $u->password = bcrypt('password');
    $u->save();
    echo "Password reset to 'password' for " . $u->email . "\n";
} else {
    echo "No user found\n";
}
