<?php

namespace App\Support;

use App\Models\SecurityAuditLog;
use Illuminate\Http\Request;

final class SecurityAudit
{
    public static function record(string $event, ?Request $request = null, array $context = []): void
    {
        $request ??= request();

        SecurityAuditLog::create([
            'user_id' => auth()->id(),
            'event' => $event,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'context' => $context,
        ]);
    }
}
