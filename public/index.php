<?php
require_once '../config/create_db.php';
require_once '../includes/header.php';

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'contributions';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $database = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO Transactions (date, account, account_type, asset_class, amount)
                VALUES (:date, :account, :account_type, :asset_class, :amount)";

        $stmt = $database->prepare($sql);

        // The data comes from the HTML form through the $_POST superglobal in PHP
        // Each form input has a 'name' attribute that becomes the key in the $_POST array

        $stmt->bindParam(':date', $_POST['date']);
        $stmt->bindParam(':account', $_POST['account']);
        $stmt->bindParam(':account_type', $_POST['account_type']);
        $stmt->bindParam(':asset_class', $_POST['asset_class']);
        $stmt->bindParam(':amount', $_POST['amount']);

        $stmt->execute();

        // redirect
        header('Location: ./?status=success');
        exit();
    } catch (PDOException $e) {
        // Encoding the error message in URL params doesn't seem like the best practice
        // header('Location: ./?status=error' . urlencode($e->getMessage()));
        
        // redirect
        header('Location: ./?status=error');
        exit();
    }
}

function getContributionSummaries($database): array {
    try {
        $yearQuery = 'SELECT SUM(amount) as total FROM Transactions WHERE YEAR(date) = YEAR(CURRENT_DATE())';
        $yearStmt = $database->query($yearQuery);
        $yearTotal = $yearStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $monthQuery = "SELECT SUM(amount) as total FROM Transactions 
                      WHERE YEAR(date) = YEAR(CURRENT_DATE()) 
                      AND MONTH(date) = MONTH(CURRENT_DATE())";
        $monthStmt = $database->query($monthQuery);
        $monthTotal = $monthStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $lastMonthQuery = "SELECT SUM(amount) as total FROM Transactions 
                          WHERE YEAR(date) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))
                          AND MONTH(date) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))";
        $lastMonthStmt = $database->query($lastMonthQuery);
        $lastMonthTotal = $lastMonthStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        return [
            'yearTotal' => $yearTotal,
            'monthTotal' => $monthTotal,
            'lastMonthTotal' => $lastMonthTotal,
        ];
    } catch (PDOException $e) {
        echo $e->getMessage();
        return [
            'yearTotal' => 0,
            'monthTotal' => 0,
            'lastMonthTotal' => 0,
        ];
    }
}

try {
    $database = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $summaries = getContributionSummaries($database);
} catch (PDOException $e) {
    $summaries = [
        'yearTotal' => 0,
        'monthTotal' => 0,
        'lastMonthTotal' => 0,
    ];
    error_log($e->getMessage());
}
?>

<div class="max-w-screen-lg mx-auto">
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-purple-200 p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-2">Contributions This Year</h2>
            <p class="text-2xl">$<?php echo number_format($summaries['yearTotal'], 0); ?></p>
        </div>
        <div class="bg-purple-200 p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-2">Contributions This Month</h2>
            <p class="text-2xl">$<?php echo number_format($summaries['monthTotal'], 0); ?></p>
        </div>
        <div class="bg-purple-200 p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-2">Contributions Last Month</h2>
            <p class="text-2xl">$<?php echo number_format($summaries['lastMonthTotal'], 0); ?></p>
        </div>
    </div>

    <div class="bg-purple-200 max-w-lg rounded-xl mx-auto mb-8">
<h1 class="text-center text-xl rounded-t-xl border-b border-black py-1">Add new contribution</h1>
<div class=" mx-auto p-6 bg-purple-200 rounded-b-xl">
    <form action="index.php" method="post" class="grid gap-6">
        <div class="grid grid-cols-2 items-center gap-4">

            <!-- Date -->
            <div class="flex flex-col space-y-2">
                <label for="date" class="text-left">Date:</label>
                <input type="date" id="date" name="date" value="<?php echo date(
                    'Y-m-d',
                ); ?>" class="p-2 border rounded"/>
            </div>

            <!-- Account -->
            <div class="flex flex-col space-y-2">
                <label for="account" class="text-left">Account:</label>
                <select name="account" id="account" class="p-2 border rounded">
                    <option value="select" disabled selected>Select Account</option>
                    <option value="tiaa">TIAA</option>
                    <option value="schwab">Schwab</option>
                    <option value="fidelity">Fidelity</option>
                    <option value="vanguard">Vanguard</option>
                    <option value="robinhood">Robinhood</option>
                </select>
            </div>

            <!-- Account Type -->
            <div class="flex flex-col space-y-2">
                <label for="account_type" class="text-left">Account Type:</label>
                <select name="account_type" id="account_type" class="p-2 border rounded">
                    <option value="select" disabled selected>Select Account Type</option>
                    <option value="retirement-403b">Retirement - 403b</option>
                    <option value="retirement-401a">Retirement - 401a</option>
                    <option value="retirement-roth-ira">Retirement - Roth IRA</option>
                    <option value="retirement-traditional-ira">Retirement - Traditional IRA</option>
                    <option value="business-taxable-brokerage">Business - Taxable Brokerage</option>
                    <option value="529-college-fund">529 College Fund</option>
                    <option value="taxable-brokerage">Taxable Brokerage</option>
                </select>
            </div>

            <!-- Investment Type -->
            <div class="flex flex-col space-y-2">
                <label for="asset_class" class="text-left">Asset Class:</label>
                <select name="asset_class" id="asset_class" class="p-2 border rounded">
                    <option value="select" disabled selected>Select Asset Class</option>
                    <option value="mutual-fund">Mutual Fund</option>
                    <option value="equities">Equities</option>
                    <option value="crypto">Crypto</option>
                </select>
            </div>

        </div>
            <!-- Amount -->
            <div class="flex flex-col space-y-2 mx-auto">
                <label for="amount" class="text-center">Amount:</label>
                <input type="number" id="amount" name="amount" class="p-2 border rounded"/>
            </div>

        <button type="submit" class="mx-auto bg-purple-400 w-full text-black py-2 px-4 rounded hover:bg-purple-500">
            Submit
        </button>
    </form>
</div>
    </div>

    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] === 'success'): ?>
            <div class="bg-green-100 mx-auto max-w-lg border border-green-400 text-center text-green-700 px-4 py-3 rounded mb-4">
                Transaction saved successfully!
            </div>
        <?php elseif ($_GET['status'] === 'error'): ?>
            <div class="bg-red-100 mx-auto max-w-lg border border-red-400 text-center text-red-700 px-4 py-3 rounded mb-4">
                <!-- Error saving transaction: --><?php
            //echo htmlspecialchars($_GET['message']);
            ?>
                Error saving transaction. Please try again.
            </div>
        <?php endif; ?>
    <?php endif; ?>

</div>

<?php require_once '../includes/footer.php'; ?>
