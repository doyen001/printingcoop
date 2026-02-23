<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Order Email Mailable
 * Used for sending order confirmation and status update emails
 */
class OrderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $orderData;
    public $OrderItemData;
    public $StoreData;
    public $cityData;
    public $stateData;
    public $countryData;
    public $heding;
    public $body;
    public $OrderCurrencyData;
    public $order_currency_currency_symbol;
    public $salesTaxRatesProvinces_Data;
    public $emailHtml;

    /**
     * Create a new message instance.
     */
    public function __construct($emailHtml, $subject = 'Order Confirmation')
    {
        $this->emailHtml = $emailHtml;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subject)
                    ->html($this->emailHtml);
    }
}
