{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}
<div class="row fork-module-heading">
  <div class="col-md-12">
    <h2>{$lblCities|sprintf:{$state.locale.name}|ucfirst}</h2>
    <div class="btn-toolbar pull-right">
      <div class="btn-group" role="group">
        {option:showGeoAddCity}
        <a href="{$var|geturl:'add_state':null:'&state_id={$state.id}'}" class="btn btn-default" title="{$lblAddCity|ucfirst}">
          <span class="glyphicon glyphicon-plus"></span>&nbsp;
          {$lblAddCity|ucfirst}
        </a>
        {/option:showGeoAddCity}
      </div>
    </div>
  </div>
</div>
<div class="row fork-module-content">
  <div class="col-md-12">
    {form:filter}
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="search">{$lblSearch|ucfirst}</label>
              {$txtSearch} {$txtSearchError}
            </div>
          </div>
        </div>
      </div>
      <div class="panel-footer">
        <div class="btn-toolbar">
          <div class="btn-group pull-right">
            <button type="submit" class="btn btn-primary">
              <span class="glyphicon glyphicon-refresh"></span>&nbsp;
              {$lblUpdateFilter|ucfirst}
            </button>
          </div>
        </div>
      </div>
    </div>
    {/form:filter}
  </div>
</div>
<div class="row fork-module-content">
  <div class="col-md-12">
    {option:dgCities}
    <form action="{$var|geturl:'mass_action_cities'}" method="get" class="forkForms">
      {$dgCities}
      <div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="{$lblDelete|ucfirst}" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <span class="modal-title h4">{$lblDelete|ucfirst}</span>
            </div>
            <div class="modal-body">
              <p>{$msgConfirmMassCitiesDelete}</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">{$lblCancel|ucfirst}</button>
              <button type="submit" class="btn btn-primary">{$lblOK|ucfirst}</button>
            </div>
          </div>
        </div>
      </div>
    </form>
    {/option:dgCities}
    {option:!dgCities}
    <p>{$msgNoItems}</p>
    {/option:!dgCities}
  </div>
</div>
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
