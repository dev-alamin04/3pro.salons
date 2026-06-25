<style> 
    .card {

        padding: 15px 15px;
    }

    .icon-align {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        padding: 20px;
        border-radius: 8px;
        border: #DFE1E7 solid 1px;
    }
</style>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="rounded-lg card">
            <div class="simgle_monitor_count metric-label d-flex justify-content-between">
                <div class="icon-align"><i class="fa fa-users fa-lg text-primary"></i></div>
                <div><a href=""><i class="fa fa-arrow-right fa-lg text-muted2"></i></a></div>
            </div>
            <h4 class="metric-value counter mt-2">{{ number_format($total_users ?? 0) }}</h4>
            <small class="text-muted2">Total Users</small>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="rounded-lg card">
            <div class="simgle_monitor_count metric-label d-flex justify-content-between">
                <div class="icon-align"><i class="fa-solid fa-layer-group fa-lg text-primary"></i></i></div>
                <div><a href=""><i class="fa fa-arrow-right fa-lg text-muted2"></i></a></div>
            </div>
            <h4 class="metric-value counter mt-2">{{ number_format($total_categories ?? 0) }}</h4>
            <small class="text-muted2">Total Categories</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="rounded-lg card">
            <div class="simgle_monitor_count metric-label d-flex justify-content-between">
                <div class="icon-align"><i class="fa-solid fa-table-list fa-lg text-primary"></i></div>
                <div><a href=""><i class="fa fa-arrow-right fa-lg text-muted2"></i></a></div>
            </div>
            <h4 class="metric-value counter mt-2">{{ number_format($total_subcategories ?? 0) }}</h4>
            <small class="text-muted2">Total Subcategories</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="rounded-lg card">
            <div class="simgle_monitor_count metric-label d-flex justify-content-between">
                <div class="icon-align"><i class="fa-brands fa-shopify fa-lg text-primary"></i></div>
                <div><a href=""><i class="fa fa-arrow-right fa-lg text-muted2"></i></a></div>
            </div>
            <h4 class="metric-value counter mt-2">{{ number_format($total_products ?? 0) }}</h4>
            <small class="text-muted2">Total Products</small>
        </div>
    </div>
</div>
