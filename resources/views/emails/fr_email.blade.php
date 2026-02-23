{{-- Generic email template (French) - replicate CI Emails/fr_email.php --}}
<div class="top-section" style="width:100%;text-align:center; font-family: Raleway, sans-serif !important;display: flex;justify-content: center;align-items: center;">
    <div class="top-mid-section" style="width:100%; max-width:100%; height:auto; text-align:center; padding:0px 0px 0px 0px; box-shadow: 0px 0px 10px -3px rgba(0,0,0,0.5);background-image: url('{{ config('app.url') }}/assets/images/bg-vector-img.jpg');">
        <div style="background: rgba(255,255,255,0.9)">
            <div class="top-inner-section" style="background: #fa762b; padding: 3px 0px 1px 0px; box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.5);"></div>
            <div style="padding: 20px 0px 10px 0px; text-align: center;"><img src="{{ $StoreData['url'] }}/uploads/logo/{{ $StoreData['email_template_logo'] }}" width="60%"></div>
            <div class="tem-mid-section" style="text-align: center;">
                <div class="tem-visibility" style="z-index: 99; padding: 20px;">
                    <div class="top-title" style="font-size: 22px; text-align: center;">
                        <span><strong>{{ $subject }}</strong></span>
                    </div>
                    <div class="email-body">
                        {!! $body !!}
                    </div>
                    <div style="background-color: #0086ac;margin-top: 20px;">
                        <div style="padding: 20px;">
                            <span style="color: #fff;line-height: 25px;">
                                {!! $StoreData['email_footer_line'] !!}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tem-bottom" style="font-size: 18px; letter-spacing: 0.5px; line-height: 30px; background: #22a641;; color: #fff; padding: 3px 0px; text-align: center;"></div>
        </div>
    </div>
</div>
