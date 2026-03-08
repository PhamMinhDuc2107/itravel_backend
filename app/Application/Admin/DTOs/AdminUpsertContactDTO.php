<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

readonly final class AdminUpsertContactDTO
{
    public function __construct(
        public string $fullName,
        public string $email,
        public ?string $phone,
        public ?string $subject,
        public string $message,
        public ?string $status,
        public ?string $adminNote,
        public ?int $resolvedBy,
        public ?string $resolvedAt,
        public ?string $ipAddress,
    ) {}

    /** @return array<string, mixed> */
    public function toPayload(): array
    {
        return [
            'full_name' => $this->fullName,
            'email' => $this->email,
            'phone' => $this->phone,
            'subject' => $this->subject,
            'message' => $this->message,
            'status' => $this->status,
            'admin_note' => $this->adminNote,
            'resolved_by' => $this->resolvedBy,
            'resolved_at' => $this->resolvedAt,
            'ip_address' => $this->ipAddress,
        ];
    }
}
