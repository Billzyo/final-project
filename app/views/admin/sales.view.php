<div class="container-fluid">
  <!-- Tabs Section -->
  <ul class="nav nav-pills mb-4">
    <li class="nav-item">
      <a class="nav-link <?=($section == 'table') ? 'active' : ''?>" href="index.php?pg=admin&tab=sales">
        <i class="fa fa-table"></i> Table View
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?=($section == 'graph') ? 'active' : ''?>" href="index.php?pg=admin&tab=sales&s=graph">
        <i class="fa fa-chart-bar"></i> Graph View
      </a>
    </li>
  </ul>

  <div class="row">
    <!-- Filter Form -->
    <?php if ($section == 'table'): ?>
      <div class="col-12 mb-3">
        <form class="d-flex justify-content-end">
          <div class="form-group me-2">
            <label for="start">Start Date:</label>
            <input class="form-control" id="start" type="date" name="start" value="<?= !empty($_GET['start']) ? $_GET['start'] : '' ?>">
          </div>
          <div class="form-group me-2">
            <label for="end">End Date:</label>
            <input class="form-control" id="end" type="date" name="end" value="<?= !empty($_GET['end']) ? $_GET['end'] : '' ?>">
          </div>
          <div class="form-group me-2">
            <label for="limit">Rows:</label>
            <input style="max-width: 80px" class="form-control" id="limit" type="number" min="1" name="limit" value="<?= !empty($_GET['limit']) ? $_GET['limit'] : '20' ?>">
          </div>
          <button class="btn btn-primary btn-sm"><i class="fa fa-chevron-right"></i> Go</button>
          <input type="hidden" name="pg" value="admin">
          <input type="hidden" name="tab" value="sales">
        </form>
      </div>

      <!-- Sales Table -->
      <div class="table-responsive">
        <h3 class="mb-3">Today's Sales: K<?= number_format($sales_total, 2) ?></h3>
        <table class="table table-bordered table-hover table-striped">
          <thead class="thead-light">
            <tr>
              <th>Barcode</th>
              <th>Receipt No</th>
              <th>Description</th>
              <th>Qty</th>
              <th>Amount</th>
              <th>Total</th>
              <th>Cashier</th>
              <th>Date</th>
              <th>
                <a href="index.php?pg=home">
                  <button class="btn btn-success btn-sm rounded-pill">
                    <i class="fa fa-plus"></i> Add New
                  </button>
                </a>
              </th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($sales)): ?>
              <?php foreach ($sales as $sale): ?>
                <tr>
                  <td><?= esc($sale['barcode']) ?></td>
                  <td><?= esc($sale['receipt_no']) ?></td>
                  <td><?= esc($sale['description']) ?></td>
                  <td><?= esc($sale['qty']) ?></td>
                  <td><?= esc($sale['amount']) ?></td>
                  <td><?= esc($sale['total']) ?></td>
                  <?php 
                    $cashier = get_user_by_id($sale['user_id']);
                    if (empty($cashier)) {
                      $name = "Unknown";
                      $namelink = "#";
                    } else {
                      $name = $cashier['username'];
                      $namelink = "index.php?pg=profile&id=" . $cashier['id'];
                    }
                  ?>
                  <td>
                    <a href="<?= $namelink ?>" class="text-decoration-none">
                      <?= esc($name) ?>
                    </a>
                  </td>
                  <td><?= date("jS M, Y", strtotime($sale['date'])) ?></td>
                  <td>
                    <a href="index.php?pg=sale-edit&id=<?= $sale['id'] ?>" class="me-2">
                      <button class="btn btn-success btn-sm rounded-pill">Edit</button>
                    </a>
                    <a href="index.php?pg=sale-delete&id=<?= $sale['id'] ?>">
                      <button class="btn btn-danger btn-sm rounded-pill">Delete</button>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>

        <!-- Total Sales Per Product -->
        <h4 class="mt-4">Total Sales Per Product</h4>
        <table class="table table-bordered table-hover table-striped">
          <thead class="thead-light">
            <tr>
              <th>Description</th>
              <th>Total Sales (K)</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($sales_total_per_product)): ?>
              <?php foreach ($sales_total_per_product as $item): ?>
                <tr>
                  <td><?= esc($item['description']) ?></td>
                  <td><?= number_format($item['total_sales'], 2) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>

        <!-- Total Sales Per Cashier -->
        <h4 class="mt-4">Total Sales Per Cashier</h4>
        <table class="table table-bordered table-hover table-striped">
          <thead class="thead-light">
            <tr>
              <th>Cashier</th>
              <th>Total Sales (K)</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($sales_total_per_cashier)): ?>
              <?php foreach ($sales_total_per_cashier as $item): ?>
                <tr>
                  <?php 
                    $cashier = get_user_by_id($item['user_id']);
                    $name = $cashier ? $cashier['username'] : 'Unknown';
                  ?>
                  <td><?= esc($name) ?></td>
                  <td><?= number_format($item['total_sales'], 2) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>

        <!-- Pagination -->
        <?php $pager->display(); ?>
      </div>

    <!-- Graph Section -->
    <?php else: ?>
      <div class="container">
        <h3 class="mb-4">Sales Graphs</h3>
        <!-- Today's Sales Graph -->
        <div class="mb-5">
          <?php
            $graph = new Graph();
            $data = generate_daily_data($today_records);
            $graph->title = "Today's Sales";
            $graph->xtitle = "Hours of the Day";
            $graph->styles = "width:80%;margin:auto;display:block";
            $graph->display($data);
          ?>
        </div>

        <!-- This Month's Sales Graph -->
        <div class="mb-5">
          <?php 
            $data = generate_monthly_data($thismonth_records);
            $graph->title = "This Month's Sales";
            $graph->xtitle = "Days of the Month";
            $graph->styles = "width:80%;margin:auto;display:block";
            $graph->display($data);
          ?>
        </div>

        <!-- This Year's Sales Graph -->
        <div class="mb-5">
          <?php 
            $data = generate_yearly_data($thisyear_records);
            $graph->title = "This Year's Sales";
            $graph->xtitle = "Months of the Year";
            $graph->styles = "width:80%;margin:auto;display:block";
            $graph->display($data);
          ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>
