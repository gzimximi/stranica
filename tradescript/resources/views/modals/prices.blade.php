<div aria-hidden="true" class="modal fade" id="pricesModal" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-xs">
        <div class="modal-content">
            <div class="modal-heading">
              <h2 class="modal-title">{{ trans('common.prices') }}</h2>
            </div>
            <div class="modal-inner">
              <p>{{ trans('common.prices_extra') }}</p>
              <table class="ui inverted table">
                <thead>
                  <tr>
                    <th>{{ trans('common.item_type') }}</th>
                    <th>{{ trans('common.your_value') }}</th>
                    <th>{{ trans('common.our_value') }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><div class="green label">{{ trans('common.keys') }}</div></td>
                    <td>100%</td>
                    <td>105%</td>
                  </tr>
                  <tr>
                    <td><div class="pink label">{{ trans('common.knives') }}</div></td>
                    <td>93%</td>
                    <td>96%</td>
                  </tr>
                  <tr>
                    <td><div class="blue label">{{ trans('common.weapons') }}</div></td>
                    <td>83%</td>
                    <td>89%</td>
                  </tr>
                  <tr>
                    <td><div class="grey label">{{ trans('common.other') }}</div></td>
                    <td>80%</td>
                    <td>86%</td>
                  </tr>
                  <tr>
                    <td><div class="brown label">{{ trans('common.trash') }}</div></td>
                    <td colspan="2">25-50%</td>
                  </tr>
                  <tr>
                    <td><div class="brown label">Souvenir</div></td>
                    <td colspan="2">Almost worthless</td>
                  </tr>
                </tbody>
              </table>
            </div>
        </div>
    </div>
</div>