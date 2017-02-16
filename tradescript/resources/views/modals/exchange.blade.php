<div aria-hidden="true" class="modal fade" id="exchangeModal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-xs">
        <div class="modal-content">
            <div class="progress margin-top-no" data-loading>
              <div class="load-bar">
                <div class="load-bar-base">
                  <div class="load-bar-content">
                    <div class="load-bar-progress"></div>
                    <div class="load-bar-progress load-bar-progress-brand"></div>
                    <div class="load-bar-progress load-bar-progress-green"></div>
                    <div class="load-bar-progress load-bar-progress-orange"></div>
                  </div>
                </div>
              </div>
              <div class="load-bar">
                <div class="load-bar-base">
                  <div class="load-bar-content">
                    <div class="load-bar-progress"></div>
                    <div class="load-bar-progress load-bar-progress-orange"></div>
                    <div class="load-bar-progress load-bar-progress-green"></div>
                    <div class="load-bar-progress load-bar-progress-brand"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-heading">
              <h2 class="modal-title">{{ trans('common.exchange_title') }}</h2>
            </div>
            <div class="modal-inner">
              <p data-loading>{{ trans('common.checking_offer') }}</p>
              <p data-loaded>{{ trans('common.offer_sent_description') }}</p>
              <p data-error>Ehmagerd, pls try again.</p>
            </div>
            <div class="modal-footer" data-loaded>
              <p class="text-right">
                <a class="btn btn-flat btn-brand waves-attach waves-button waves-effect" href="#" data-offerlink target="_blank">{{ trans('common.accept_offer') }}</a>
              </p>
            </div>
        </div>
    </div>
</div>