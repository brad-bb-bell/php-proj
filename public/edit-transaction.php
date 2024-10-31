<?php
require_once('../includes/header.php');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "contributions";

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
} else {
    header('Location: /transactions.php?status=error');
    exit();
}

try {
    $database = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "Select * FROM Transactions WHERE id = :id";
    $stmt = $database->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
    $transaction = null;
}
?>

<div class="max-w-screen-lg mx-auto">
    <div class="bg-purple-200 max-w-lg rounded-xl mx-auto mb-8">
        <h1 class="text-center text-xl rounded-t-xl border-b border-black py-1">Edit contribution</h1>
        <div class=" mx-auto p-6 bg-purple-200 rounded-b-xl">
            <form action="edit-transaction.php" method="post" class="grid gap-6">
                <div class="grid grid-cols-2 items-center gap-4">

                    <!-- Date -->
                    <div class="flex flex-col space-y-2">
                        <label for="date" class="text-left">Date:</label>
                        <input type="date" id="date" name="date" value="<?php echo date('Y-m-d', strtotime($transaction['date'])); ?>" class="p-2 border rounded"/>
                    </div>

                    <!-- Account -->
                    <div class="flex flex-col space-y-2">
                        <label for="account" class="text-left">Account:</label>
                        <select name="account" id="account" class="p-2 border rounded">
                            <option value="select" disabled>Select Account</option>
                            <option value="tiaa" <?php echo ($transaction['account'] === 'tiaa') ? 'selected' : ''; ?>>TIAA</option>
                            <option value="schwab" <?php echo ($transaction['account'] === 'schwab') ? 'selected' : ''; ?>>Schwab</option>
                            <option value="fidelity" <?php echo ($transaction['account'] === 'fidelity') ? 'selected' : ''; ?>>Fidelity</option>
                            <option value="vanguard" <?php echo ($transaction['account'] === 'vanguard') ? 'selected' : ''; ?>>Vanguard</option>
                            <option value="robinhood" <?php echo ($transaction['account'] === 'robinhood') ? 'selected' : ''; ?>>Robinhood</option>
                        </select>
                    </div>

                    <!-- Account Type -->
                    <div class="flex flex-col space-y-2">
                        <label for="account_type" class="text-left">Account Type:</label>
                        <select name="account_type" id="account_type" class="p-2 border rounded">
                            <option value="select" disabled>Select Account Type</option>
                            <option value="retirement-403b" <?php echo ($transaction['account_type'] === 'retirement-403b') ? 'selected' : ''; ?>>Retirement - 403b</option>
                            <option value="retirement-401a" <?php echo ($transaction['account_type'] === 'retirement-401a') ? 'selected' : ''; ?>>Retirement - 401a</option>
                            <option value="retirement-roth-ira" <?php echo ($transaction['account_type'] === 'retirement-roth-ira') ? 'selected' : ''; ?>>Retirement - Roth IRA</option>
                            <option value="retirement-traditional-ira" <?php echo ($transaction['account_type'] === 'retirement-traditional-ira') ? 'selected' : ''; ?>>Retirement - Traditional IRA</option>
                            <option value="business-taxable-brokerage" <?php echo ($transaction['account_type'] === 'business-taxable-brokerage') ? 'selected' : ''; ?>>Business - Taxable Brokerage</option>
                            <option value="529-college-fund" <?php echo ($transaction['account_type'] === '529-college-fund') ? 'selected' : ''; ?>>529 College Fund</option>
                            <option value="taxable-brokerage" <?php echo ($transaction['account_type'] === 'taxable-brokerage') ? 'selected' : ''; ?>>Taxable Brokerage</option>
                        </select>
                    </div>

                    <!-- Investment Type -->
                    <div class="flex flex-col space-y-2">
                        <label for="asset_class" class="text-left">Asset Class:</label>
                        <select name="asset_class" id="asset_class" class="p-2 border rounded">
                            <option value="select" disabled>Select Asset Class</option>
                            <option value="mutual-fund" <?php echo ($transaction['asset_class'] === 'mutual-fund') ? 'selected' : ''; ?>>Mutual Fund</option>
                            <option value="equities" <?php echo ($transaction['asset_class'] === 'equities') ? 'selected' : ''; ?>>Equities</option>
                            <option value="crypto" <?php echo ($transaction['asset_class'] === 'crypto') ? 'selected' : ''; ?>>Crypto</option>
                        </select>
                    </div>

                </div>
                <!-- Amount -->
                <div class="flex flex-col space-y-2 mx-auto">
                    <label for="amount" class="text-center">Amount:</label>
                    <input type="number" id="amount" name="amount" value="<?php echo number_format($transaction['amount']); ?>" class="p-2 border rounded" />
                </div>

                <button type="submit" class="mx-auto bg-purple-400 w-full text-black py-2 px-4 rounded hover:bg-purple-500">
                    Submit
                </button>
            </form>
        </div>
    </div>
</div>