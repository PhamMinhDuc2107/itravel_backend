<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

abstract class BaseMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->onQueue('emails');
    }

    abstract protected function subject(): string;

    abstract protected function view(): string;

    public function build(): self
    {
        return $this
            ->subject($this->subject())
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->view($this->view())
            ->with($this->getData());
    }

    public function attachFile(string $path): static
    {
        $this->attach($path);

        return $this;
    }

    protected function getData(): array
    {
        return $this->data;
    }
}
