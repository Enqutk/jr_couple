@props([
    'product',
    'payment' => [],
    'phones' => [],
])

@php
    $price = $product->formattedPrice();
    $amount = $product->priceAmount();
    $telebirr = $payment['telebirr_number'] ?? null;
    $telebirrName = $payment['telebirr_name'] ?? ($data['siteName'] ?? 'JR');
    $bankName = $payment['bank_name'] ?? null;
    $bankAccount = $payment['bank_account'] ?? null;
    $bankAccountName = $payment['bank_account_name'] ?? null;
    $note = $payment['payment_note'] ?? null;
    $primaryPhone = $phones[0] ?? null;
    $phoneDigits = $primaryPhone ? preg_replace('/\D+/', '', $primaryPhone) : null;
    $whatsappText = rawurlencode("Hi JR, I want to pay for: {$product->name}".($amount ? " (ETB ".number_format($amount, 0).")" : '').'. Please confirm availability.');
@endphp

<section class="hz-store-payment" id="payment">
    <div class="hz-store-payment-head">
        <div>
            <p class="hz-store-payment-eyebrow">Pay in Ethiopian Birr</p>
            <h2 class="hz-store-payment-price">{{ $price }}</h2>
            @if($product->is_negotiable)
                <span class="hz-store-payment-chip">Negotiable</span>
            @endif
        </div>
        <div class="hz-store-payment-currency">ETB</div>
    </div>

    <p class="hz-store-payment-copy">Choose how you want to pay. After payment, send us your receipt on WhatsApp or call to confirm your order.</p>

    <div class="hz-store-payment-methods">
        @if($telebirr)
            <div class="hz-store-payment-method">
                <div class="hz-store-payment-method-icon">
                    <i class="bi bi-phone-fill"></i>
                </div>
                <div>
                    <strong>Telebirr</strong>
                    <p>Send <strong>{{ $price }}</strong> to <strong>{{ $telebirr }}</strong> ({{ $telebirrName }})</p>
                    <button type="button" class="hz-store-copy-btn" data-copy-text="{{ $telebirr }}">
                        <i class="bi bi-clipboard"></i> Copy number
                    </button>
                </div>
            </div>
        @endif

        @if($bankName && $bankAccount)
            <div class="hz-store-payment-method">
                <div class="hz-store-payment-method-icon">
                    <i class="bi bi-bank"></i>
                </div>
                <div>
                    <strong>Bank transfer</strong>
                    <p>{{ $bankName }}</p>
                    <p>Account: <strong>{{ $bankAccount }}</strong>@if($bankAccountName) · {{ $bankAccountName }}@endif</p>
                    <button type="button" class="hz-store-copy-btn" data-copy-text="{{ $bankAccount }}">
                        <i class="bi bi-clipboard"></i> Copy account
                    </button>
                </div>
            </div>
        @endif

        <div class="hz-store-payment-method">
            <div class="hz-store-payment-method-icon">
                <i class="bi bi-shop"></i>
            </div>
            <div>
                <strong>Pay in store</strong>
                <p>Visit JR and pay cash or card at the counter.</p>
            </div>
        </div>
    </div>

    <div class="hz-store-payment-actions">
        @if($phoneDigits)
            <a href="https://wa.me/{{ ltrim($phoneDigits, '+') }}?text={{ $whatsappText }}" class="btn-hz" target="_blank" rel="noopener">
                <i class="bi bi-whatsapp"></i> Pay via WhatsApp
            </a>
        @endif
        @if($primaryPhone)
            <a href="tel:{{ preg_replace('/\s+/', '', $primaryPhone) }}" class="btn-hz-outline">
                <i class="bi bi-telephone"></i> Call to pay
            </a>
        @endif
    </div>

    @if($note)
        <p class="hz-store-payment-note">{{ $note }}</p>
    @endif
</section>
