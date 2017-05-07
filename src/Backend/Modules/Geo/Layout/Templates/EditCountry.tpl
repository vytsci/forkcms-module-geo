{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}
<div class="row fork-module-heading">
  <div class="col-md-12">
    <h2>
      {$lblEditCountry|ucfirst}
    </h2>
  </div>
</div>
{form:editCountry}
  <div class="row fork-module-content">
    <div class="col-md-12">
      <div class="form-group">
        <ul class="list-unstyled">
          <li class="checkbox">
            <label for="hidden">{$chkHidden} {$lblHidden|ucfirst}</label>
          </li>
        </ul>
      </div>
      <div class="form-group">
        <label for="continentCode">{$lblContinentCode|ucfirst}</label>
        {$txtContinentCode} {$txtContinentCodeError}
      </div>
      <div class="form-group">
        <label for="isoNumeric">{$lblIsoNumeric|ucfirst}</label>
        {$txtIsoNumeric} {$txtIsoNumericError}
      </div>
      <div class="form-group">
        <label for="isoAlpha3">{$lblIsoAlpha3|ucfirst}</label>
        {$txtIsoAlpha3} {$txtIsoAlpha3Error}
      </div>
      <div class="form-group">
        <label for="postalCodeFormat">{$lblPostalCodeFormat|ucfirst}</label>
        {$txtPostalCodeFormat} {$txtPostalCodeFormatError}
      </div>
      <div class="form-group">
        <label for="currencyCode">{$lblCurrencyCode|ucfirst}</label>
        {$txtCurrencyCode} {$txtCurrencyCodeError}
      </div>
    </div>
  </div>
  <div class="row fork-module-content">
    <div class="col-md-12">
      <div class="panel-group" id="languages" role="tablist" aria-multiselectable="true">
        {iteration:formLocalization}
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="heading{$formLocalization.code|ucfirst}">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#languages" href="#collapse{$formLocalization.code|ucfirst}" aria-expanded="true" aria-controls="collapse{$formLocalization.code|ucfirst}">
                {$formLocalization.title|ucfirst}
              </a>
            </h4>
          </div>
          <div id="collapse{$formLocalization.code|ucfirst}" class="panel-collapse collapse{option:formLocalization.first} in{/option:formLocalization.first}" role="tabpanel" aria-labelledby="heading{$formLocalization.code|ucfirst}">
            <div class="panel-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="name">
                      {$lblName|ucfirst}
                      <abbr class="glyphicon glyphicon-asterisk" title="{$lblRequiredField|ucfirst}"></abbr>
                    </label>
                    {$formLocalization.fields.name}
                    {$formLocalization.errors.name}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        {/iteration:formLocalization}
      </div>
    </div>
  </div>
  <div class="row fork-module-actions">
    <div class="col-md-12">
      <div class="btn-toolbar">
        <div class="btn-group pull-right" role="group">
          <button id="saveButton" type="submit" name="edit" class="btn btn-primary">
            <span class="glyphicon glyphicon-pencil"></span>&nbsp;
            {$lblSave|ucfirst}
          </button>
        </div>
      </div>
    </div>
  </div>
{/form:editCountry}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
