<?php
require_once '../includes/header.php';

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'contributions';

// Get filter parameters with defaults
$sortby = $_GET['sortby'] ?? 'date';
$order = $_GET['order'] ?? 'desc';
$itemsPerPage = $_GET['items'] ?? '10';
$dateFrom = $_GET['date_from'] ?? date('Y-m-d', strtotime('-1 year'));
$dateTo = $_GET['date_to'] ?? date('Y-m-d');
$currentPage = max(1, $_GET['page'] ?? 1);

try {
    $database = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // build the base query
    $baseQuery = "SELECT id, date, accoutn, account_type, asset_class, amount FROM Transactions WHERE date BETWEEN :date_from AND :date_to";
    // add sorting
    $baseQuery .= " ORDER BY $sortby $order";

    // first get the total count for pagination
    $countQuery = str_replace("id, date, account, account_type, asset_class, amount", "COUNT(*) as count", $baseQuery);
    $countStmt = $database->prepare($countQuery);
    $countStmt->execute([
        ':date_from' => $dateFrom,
        ':date_to' => $dateTo
    ]);
    $totalCount = $countStmt->fetch(PDO::FETCH_ASSOC)['count'];

    // calculate pagination
    $itemsPerPage = $itemsPerPage === 'ALL' ? $totalCount : (int)$itemsPerPage;
    $totalPages = ceil($totalCount / $itemsPerPage);
    $offset = ($currentPage - 1) * $itemsPerPage;

    // add pagination to query if not showing all
    if ($itemsPerPage !== $totalCount) {
        $baseQuery .= " LIMIT :limit OFFSET :offset";
    }

    // prepare and execute main query
    $stmt = $database->prepare($baseQuery);
    $stmt->bindParam(':date_from', $dateFrom);
    $stmt->bindParam(':date_to', $dateTo);
    if ($itemsPerPage !== $totalCount) {
        $stmt->bindParam(':limit', $itemsPerPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    }
    $stmt->execute();
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // get the total amount for filtered results
    $totalQuery = "SELECT SUM(amount) as total FROM Transactions WHERE date BETWEEN :date_from AND :date_to";
    $totalStmt = $database->prepare($totalQuery);
    $totalStmt->execute([
        ':date_from' => $dateFrom,
        ':date_to' => $dateTo
    ]);
    $total = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
    $transactions = [];
    $total = 0;
    $totalPages = 0;
}
// ----------------------------------
// WHY NOT $database->prepare($query)
// $stmt = $database->query($query);
// query() is used for direct, simple SQL queries that don't have any user input or variables
// prepare() is used when you have params/variables in your query that need to be safely inserted

// WHY NOT THIS? $transactions = $stmt->execute();
// $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
// execute() just runs the query but doesn't return the results
// fetch() or fetchALL() actually retrieves the data
// $stmt->execute() would just return TRUE or FALSE
// The FETCH_ASSOC part tells PDO to return the results as an associative array where you can access columns by name like $transaction['date'] instead of numeric indices.
// ----------------------------------

// The following function uses type declaration ': string'
// The function will throw an error if a string is not returned
// This improves code documentation, better IDE support and type safety
function formatAccount($account): string {
    // ucfirst() is Uppercase First
    return $account === 'tiaa' ? 'TIAA' : ucfirst($account);
}

function formatAccountType($type): string {
    // Split by dash, capitalize each word, implode with '-'

    if ($type === 'business-taxable-brokerage') {
        return 'Business - Taxable Brokerage';
    }
    if ($type === '529-college-fund') {
        return '529 College Fund';
    }

    $words = explode('-', $type);
    $words = array_map(function ($word) {
        return ucfirst($word);
    }, $words);

    // Special handling for 'ira'
    $words = array_map(function ($word) {
        $acronyms = ['ira'];
        return in_array(strtolower($word), $acronyms) ? strtoupper($word) : $word;
    }, $words);

    return implode(' - ', $words);
}

function formatInvestmentType($type): string {
    // Split by dash, capitalize each word, implode with ' '
    $words = explode('-', $type);
    $words = array_map(function ($word) {
        return ucfirst($word);
    }, $words);

    return implode(' ', $words);
}
?>



    <div class="max-w-screen-lg mx-auto ">
        <h1 class="text-2xl font-bold mb-4 text-center text-purple-50">Transactions</h1>
        <div class="grid grid-cols-3 gap-4 mb-8 mx-auto w-2/3">
            <div class="flex flex-col space-y-2">
                <label for="items-per-page" class="text-left text-purple-50">Transactions per page:</label>
                <select name="items-per-page" id="items-per-page" class="h-10 p-2 border rounded">
                    <option value="select" disabled selected>Items Per Page</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="ALL">ALL</option>
                </select>
            </div>
            <div class="flex flex-col space-y-2 ">
                <label for="date-from" class="text-left text-purple-50">From:</label>
                <input type="date" id="date-from" name="from" value="2024-01-01" class="h-10 p-2 border rounded"/>
            </div>
            <div class="flex flex-col space-y-2">
                <label for="date-from" class="text-left text-purple-50">To:</label>
                <input type="date" id="date-from" name="from" value="<?php echo date(
                    'Y-m-d',
                ); ?>" class="h-10 p-2 border rounded"/>
            </div>
        </div>

        <table class="border-2 border-black w-full bg-purple-200 text-black rounded mb-8">
            <thead class="border-2 border-black">
            <form action="/php-proj/public/transactions.php" method="get">
                <!-- Single set of hidden inputs at the form level -->
                <input type="hidden" name="sortby" value="">
                <input type="hidden" name="order" value="<?php echo isset($_GET['order']) && $_GET['order'] === 'asc'
                    ? 'desc'
                    : 'asc'; ?>">
                <tr>
                    <th>
                        <span class="inline-flex items-center">
                            Date <button type="submit" onclick="document.querySelector('input[name=sortby]').value='date'"><img src="../assets/icons/arrow-down-up.svg" class="w-3 ml-2" alt="Sort by date"></button>
                        </span>
                    </th>
                    <th>
                        <span class="inline-flex items-center">
                            Account <button type="submit" onclick="document.querySelector('input[name=sortby]').value='account'"><img src="../assets/icons/arrow-down-up.svg" class="w-3 ml-2" alt="Sort by account"></button>
                        </span>
                    </th>
                    <th>
                        <span class="inline-flex items-center">
                            Account Type <button type="submit" onclick="document.querySelector('input[name=sortby]').value='account_type'"><img src="../assets/icons/arrow-down-up.svg" class="w-3 ml-2" alt="Sort by account type"></button>
                        </span>
                    </th>
                    <th>
                        <span class="inline-flex items-center">
                            Asset Class <button type="submit" onclick="document.querySelector('input[name=sortby]').value='asset_class'"><img src="../assets/icons/arrow-down-up.svg" class="w-3 ml-2" alt="Sort by asset class">                            </button>
                        </span>
                    </th>
                    <th>
                        <span class="inline-flex items-center">
                            Amount <button type="submit" onclick="document.querySelector('input[name=sortby]').value='amount'"><img src="../assets/icons/arrow-down-up.svg" class="w-3 ml-2" alt="Sort by Amount">                            </button>
                        </span>
                    </th>
                <th><!-- empty slot for edit --></th>
                <th><!-- empty slot fo delete --></th>
            </tr>
        </form>
        </thead>
        <tbody>
        <?php foreach ($transactions as $transaction): ?>
        <tr class="odd:bg-purple-100">
            <!-- $transaction['date'] returns '2024-10-31' -->
            <!-- strtotime() converts it to a timestamp (like 1698710400) -->
            <!-- date() formats it -->
            <td><?php echo date('m/d/y', strtotime($transaction['date'])); ?></td>
            <!-- always use htmlspecialchars() when outputting user-provided data to prevent xss attacks -->
            <td><?php echo htmlspecialchars(formatAccount($transaction['account'])); ?></td>
            <td><?php echo htmlspecialchars(formatAccountType($transaction['account_type'])); ?></td>
            <td><?php echo htmlspecialchars(formatInvestmentType($transaction['asset_class'])); ?></td>
            <td>$<?php echo number_format($transaction['amount']); ?></td>
            <td class="p-0">
                <form action="/php-proj/public/edit.php" method="get">
                    <input type="hidden" name="id" value="<?php echo $transaction['id']; ?>">
                    <button type="submit"
                            class="border border-purple-400 px-1 py-0.5 rounded hover:bg-purple-300">
                        Edit
                    </button>
                </form>
            </td>
            <td class="p-0">
                <form action="/php-proj/public/delete.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $transaction['id']; ?>">
                    <button type="submit"
                            class="border border-purple-400 px-1 py-0.5 rounded hover:bg-purple-300"
                            onclick="return confirm('Are you sure you want to delete this transaction? This action cannot be undone.');">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot class="border-2 border-black">
        <tr>
            <td colspan="4">Total Contributions</td>
            <td>$<?php echo number_format($total); ?></td>
        </tr>
        </tfoot>
    </table>
        
    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] === 'success'): ?>
            <div class="bg-green-100 mx-auto max-w-lg border border-green-400 text-center text-green-700 px-4 py-3 rounded mb-4">
                Transaction successfully updated.
            </div>
        <?php elseif ($_GET['status'] === 'deleted'): ?>
            <div class="bg-green-100 mx-auto max-w-lg border border-green-400 text-center text-green-700 px-4 py-3 rounded mb-4">
                Transaction successfully deleted.
            </div>
        <?php elseif ($_GET['status'] === 'error'): ?>
            <div class="bg-red-100 mx-auto max-w-lg border border-red-400 text-center text-red-700 px-4 py-3 rounded mb-4">
                Error updating transaction. Please try again.
            </div>
        <?php elseif ($_GET['status'] === 'failed'): ?>
            <div class="bg-red-100 mx-auto max-w-lg border border-red-400 text-center text-red-700 px-4 py-3 rounded mb-4">
                Error deleting transaction. Please try again.
            </div>
        <?php endif; ?>
    <?php endif; ?>

    </div>
<?php require_once '../includes/footer.php'; ?>
