<?php
require_once '../includes/header.php';

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'contributions';

try {
    $database = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = 'SELECT id, date, account, account_type, asset_class, amount FROM Transactions ORDER BY date DESC';

    // WHY NOT $database->prepare($query)
    $stmt = $database->query($query);
    // query() is used for direct, simple SQL queries that don't have any user input or variables
    // prepare() is used when you have params/variables in your query that need to be safely inserted

    // WHY NOT THIS? $transactions = $stmt->execute();
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // execute() just runs the query but doesn't return the results
    // fetch() or fetchALL() actually retrieves the data
    // $stmt->execute() would just return TRUE or FALSE

    // The FETCH_ASSOC part tells PDO to return the results as an associative array where you can access columns by name like $transaction['date'] instead of numeric indices.

    $totalQuery = 'SELECT SUM(amount) as total FROM Transactions';
    $totalStmt = $database->query($totalQuery);
    $total = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
    $transactions = [];
    $total = 0;
}

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

    <div class="max-w-screen-lg mx-auto">
    <table class="border-2 border-black w-full bg-purple-200 text-black rounded mb-8">
        <thead class="border-2 border-black">
        <tr>
            <th>Date</th>
            <th>Account</th>
            <th>Account Type</th>
            <th>Investment Type</th>
            <th>Amount</th>
        </tr>
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
