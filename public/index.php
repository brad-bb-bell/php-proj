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

        $sql = "INSERT INTO Transactions (date, account, account_type, investment_type, amount)
                VALUES (:date, :account, :account_type, :investment_type, :amount)";

        $stmt = $database->prepare($sql);

        $stmt->bindParam(':date', $_POST['date']);
        $stmt->bindParam(':account', $_POST['account']);
        $stmt->bindParam(':account_type', $_POST['account_type']);
        $stmt->bindParam(':investment_type', $_POST['investment_type']);
        $stmt->bindParam(':amount', $_POST['amount']);

        $stmt->execute();

        header('Location: ./?status=success');
        exit();
    } catch (PDOException $e) {
        header('Location: ./?status=error&message=' . urlencode($e->getMessage()));
        exit();
    }
}

function getContributionSummaries($database) {
    try {
        $yearQuery = "SELECT SUM(amount) as total FROM Transactions WHERE YEAR(date) = YEAR(CURRENT_DATE())";
        $yearStmt = $database->query($yearQuery);
        $yearTotal = $yearStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $monthQuery = "SELECT SUM(amount) as total FROM Transactions WHERE YEAR(date) = YEAR(CURRENT_DATE()) AND MONTH(date) = MONTH(CURRENT_DATE())";
        $monthStmt = $database->query($monthQuery);
        $monthTotal = $monthStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $lastMonthQuery = "SELECT SUM(amount) as total FROM Transactions WHERE >= DATE_SUB(DATE_FORMAT(CURRENT_DATE() '%Y-%m-01') INTERVAL 1 MONTH) AND date < DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01')";
        $lastMonthStmt = $database->query($lastMonthQuery);
        $lastMonthTotal = $lastMonthStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        return [
            'yearTotal' => $yearTotal,
            'monthTotal' => $monthTotal,
            'lastMonthTotal' => $lastMonthTotal,
        ];
    } catch(PDOException $e) {
        echo $e->getMessage();
        return [
            'yearTotal' => 0,
            'monthTotal' => 0,
            'lastMonthTotal' => 0,
        ];
    }
}
?>

<div class="max-w-screen-lg mx-auto">
    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] === 'success'): ?>
            <div class="bg-green-100 border border-green-400 text-center text-green-700 px-4 py-3 rounded mb-4">
                Transaction saved successfully!
            </div>
        <?php elseif ($_GET['status'] === 'error'): ?>
            <div class="bg-red-100 border border-red-400 text-center text-red-700 px-4 py-3 rounded mb-4">
                Error saving transaction: <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <div class="bg-amber-50 max-w-lg rounded-xl mx-auto mb-8">
<h1 class="text-center text-xl rounded-t-xl border-b border-black">Add new contribution</h1>
<div class=" mx-auto p-6 bg-amber-50 rounded-b-xl">
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
                <label for="investment_type" class="text-left">Investment Type:</label>
                <select name="investment_type" id="investment_type" class="p-2 border rounded">
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


</div>

<?php require_once '../includes/footer.php'; ?>
