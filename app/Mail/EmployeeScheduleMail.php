<?php

namespace App\Mail;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmployeeScheduleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;
    public $date;
    public $hourlySchedule;

    /**
     * Create a new message instance.
     */
    public function __construct(Employee $employee, $date, $hourlySchedule)
    {
        $this->employee = $employee;
        $this->date = $date;
        $this->hourlySchedule = $hourlySchedule;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.employees.schedule')
                    ->subject('📅 Schedule for ' . $this->employee->full_name . ' on ' . $this->date . ' 🕒')
                    ->with([
                        'employee' => $this->employee,
                        'date' => $this->date,
                        'hourlySchedule' => $this->hourlySchedule,
                    ]);
    }
}
