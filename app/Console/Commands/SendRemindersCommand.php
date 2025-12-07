<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ReminderSetting;
use App\Models\ReminderLog;
use App\Models\ProcedureRequest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class SendRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía recordatorios automáticos según la configuración del sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $settings = ReminderSetting::first();

        if (!$settings || !$settings->enabled) {
            $this->info("Recordatorios desactivados.");
            return Command::SUCCESS;
        }

        $hoy = Carbon::today();

        // Buscar trámites próximos a vencer
        $tramites = ProcedureRequest::where('status', 'pendiente')
            ->whereDate('deadline', '<=', $hoy->copy()->addDays($settings->interval_days))
            ->get();

        foreach ($tramites as $t) {

            $worker = User::find($t->user_id);
            if (!$worker) continue;

            $message = $settings->base_message;
            $channel = $settings->channel; // email | inapp

            // Evitar duplicados en el mismo día
            $yaEnviado = ReminderLog::where('user_id', $worker->id)
                ->where('procedure_id', $t->id)
                ->where('sent_at', $hoy->toDateString())
                ->exists();

            if ($yaEnviado) continue;

            // ENVIAR POR CORREO
            if ($channel === 'email' && $worker->email) {
                Mail::raw($message, function ($msg) use ($worker) {
                    $msg->to($worker->email)
                        ->subject('Recordatorio de trámite');
                });
            }

            // NOTIFICACIÓN INTERNA
            if ($channel === 'inapp') {
                Notification::send(
                    $worker,
                    new \App\Notifications\ProcedureReminderNotification($message)
                );
            }

            // REGISTRAR ENVÍO
            ReminderLog::create([
                'user_id'     => $worker->id,
                'procedure_id'=> $t->id,
                'channel'     => $channel,
                'message'     => $message,
                'sent_at'     => $hoy->toDateString(),
            ]);
        }

        $this->info("Recordatorios enviados correctamente.");
        return Command::SUCCESS;
    }
}
