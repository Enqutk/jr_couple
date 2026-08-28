<!-- footer -->
<footer class="site-footer pbmit-footer-style-1 pbmit-bg-color-blackish">
	<div class="pbmit-footer-big-area-wrapper">
		<div class="pbmit-footer-big-area">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-md-12 col-lg-6 pbmit-footer-left">
						<h3>Delivering excellence, one project at a time.</h3>
					</div>
					<div class="col-md-12 col-lg-6 text-lg-end">
						<div class="pbmit-footer-logo">
							<img class="pbmit-main-logo" src="{{ asset('assets/images/logo/logo-05.png') }}" alt="">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="pbmit-footer-contact-area-wrapper">
		<div class="pbmit-footer-contact-area">
			<div class="container">
				<div class="row">
					<div class="col-md-12 col-lg-4 pbmit-footer-contact-box">
						@if(!empty($data['address']))
						{{ $data['address'] }}
						@else
						<span class="text-muted">No address configured</span>
						@endif
					</div>
					<div class="col-md-12 col-lg-4 pbmit-footer-contact-box d-flex flex-column">
						@if(!empty($data['email']))
						@foreach($data['email'] as $email)
						<a href="mailto:{{ $email }}" class="__cf_email__">{{ $email }}</a>
						@if(!$loop->last)<br>@endif
						@endforeach
						@else
						<span class="text-muted">No email addresses configured</span>
						@endif
					</div>
					<div class="col-md-12 col-lg-4 pbmit-footer-contact-box d-flex flex-column">
						@if(!empty($data['phone']))
						@foreach($data['phone'] as $phone)
						{{ $phone }}
						@if(!$loop->last)<br>@endif
						@endforeach
						@else
						<span class="text-muted">No phone numbers configured</span>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="pbmit-footer-widget-area">
	
	</div>
	<div class="pbmit-footer-text-area">
		<div class="container">
			<div class="pbmit-footer-text-inner">
				<div class="row">
					<div class="col-md-6">
						<div class="pbmit-footer-copyright-text-area">
							<div class="copyright-text">
								<a href=".">{{ $data['siteName'] ?? config('app.name') }}</a><span class="fw-bold"> © {{ date('Y') }}</span>. All Right Reserved
								Developed By <a href="https://tetercreatives.com" target="_blank">Teter PLC</a>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class=" pbmit-footer-social-area">
						  <x-social-media />
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</footer>
<!-- footer End -->
