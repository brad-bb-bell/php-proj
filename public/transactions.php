<?php
require_once '../includes/header.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "contributions";

try {
    $database = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT date, account, account_type, investment_type, amount FROM Transactions ORDER BY date DESC";

    // WHY NOT $database->prepare($query)
    $stmt = $database->query($query);

    // WHY NOT THIS? $transactions = $stmt->execute();
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalQuery = "SELECT SUM(amount) as total FROM Transactions";
    $totalStmt = $database->query($totalQuery);
    $total = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    $transactions = [];
    $total = 0;
}

// Helper functions for formatting
function formatAccount($account) {
    return strtoupper($account);
}

function formatAccountType($type) {
    // Split by dash and capitalize each word
    $words = explode('-', $type);
    $words = array_map(function($word) {
        return ucfirst($word);
    }, $words);

    // Special handling for specific acronyms
    $words = array_map(function($word) {
        $acronyms = ['ira'];
        return in_array(strtolower($word), $acronyms)
            ? strtoupper($word)
            : $word;
    }, $words);

    return implode(' - ', $words);
}

function formatInvestmentType($type) {
    // Split by dash and capitalize each word
    $words = explode('-', $type);
    $words = array_map(function($word) {
        return ucfirst($word);
    }, $words);

    return implode(' ', $words);
}
?>
?>
    <div class="max-w-screen-lg mx-auto">
    <table class="border-2 border-black w-full bg-purple-200 text-black rounded">
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
        <?php foreach ($transactions as $transaction) : ?>
        <tr>
            <td class="p-2"><?php echo date('m/d/Y', strtotime($transaction['date'])); ?></td>
            <td class="p-2"><?php echo htmlspecialchars(formatAccount($transaction['account'])); ?></td>
            <td class="p-2"><?php echo htmlspecialchars(formatAccountType($transaction['account_type'])); ?></td>
            <td class="p-2"><?php echo htmlspecialchars(formatInvestmentType($transaction['investment_type'])); ?></td>
            <td class="p-2">$<?php echo number_format($transaction['amount'], 2); ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot class="border-2 border-black">
        <tr>
            <td colspan="4">Total Contributions</td>
            <td>$<?php echo number_format($total, 2); ?></td>
        </tr>
        </tfoot>
    </table>
    </div>
<?php require_once '../includes/footer.php'; ?>
