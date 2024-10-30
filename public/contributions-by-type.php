<?php
require_once '../includes/header.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "contributions";
function getContributionsSummary($database) {
    try {
        // Account Types
        $retirementQuery = "SELECT SUM(amount) as total FROM Transactions 
                           WHERE account_type LIKE 'retirement-%'";
        $retirementStmt = $database->query($retirementQuery);
        $retirementTotal = $retirementStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $businessQuery = "SELECT SUM(amount) as total FROM Transactions 
                         WHERE account_type = 'business-taxable-brokerage'";
        $businessStmt = $database->query($businessQuery);
        $businessTotal = $businessStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $collegeQuery = "SELECT SUM(amount) as total FROM Transactions 
                        WHERE account_type = '529-college-fund'";
        $collegeStmt = $database->query($collegeQuery);
        $collegeTotal = $collegeStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $brokerageQuery = "SELECT SUM(amount) as total FROM Transactions 
                          WHERE account_type = 'taxable-brokerage'";
        $brokerageStmt = $database->query($brokerageQuery);
        $brokerageTotal = $brokerageStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        // Asset Classes
        $mutualFundQuery = "SELECT SUM(amount) as total FROM Transactions 
                           WHERE asset_class = 'mutual-fund'";
        $mutualFundStmt = $database->query($mutualFundQuery);
        $mutualFundTotal = $mutualFundStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $equitiesQuery = "SELECT SUM(amount) as total FROM Transactions 
                         WHERE asset_class = 'equities'";
        $equitiesStmt = $database->query($equitiesQuery);
        $equitiesTotal = $equitiesStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $cryptoQuery = "SELECT SUM(amount) as total FROM Transactions 
                       WHERE asset_class = 'crypto'";
        $cryptoStmt = $database->query($cryptoQuery);
        $cryptoTotal = $cryptoStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        return [
            'accountTypes' => [
                'retirementTotal' => $retirementTotal,
                'businessTotal' => $businessTotal,
                'collegeTotal' => $collegeTotal,
                'brokerageTotal' => $brokerageTotal
            ],
            'assetClasses' => [
                'mutualFundTotal' => $mutualFundTotal,
                'equitiesTotal' => $equitiesTotal,
                'cryptoTotal' => $cryptoTotal
            ]
        ];
    } catch(PDOException $e) {
        echo $e->getMessage();
        return [
            'accountTypes' => [
                'retirementTotal' => 0,
                'businessTotal' => 0,
                'collegeTotal' => 0,
                'brokerageTotal' => 0
            ],
            'assetClasses' => [
                'mutualFundTotal' => 0,
                'equitiesTotal' => 0,
                'cryptoTotal' => 0
            ]
        ];
    }
}

// Get the totals
try {
    $database = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $summaries = getContributionsSummary($database);
} catch(PDOException $e) {
    echo $e->getMessage();
    $summaries = [
        'accountTypes' => [
            'retirementTotal' => 0,
            'businessTotal' => 0,
            'collegeTotal' => 0,
            'brokerageTotal' => 0
        ],
        'assetClasses' => [
            'mutualFundTotal' => 0,
            'equitiesTotal' => 0,
            'cryptoTotal' => 0
        ]
    ];
}
?>
    <div class="max-w-screen-lg mx-auto">
    <!-- Account Types Section -->
    <h1 class="text-2xl font-bold mb-4 text-center text-purple-50">Contributions by Account Type</h1>
    <div class="grid grid-cols-2 gap-4 mb-8">
        <div class="bg-purple-200 p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-2">Retirement Accounts</h2>
            <p class="text-2xl">$<?php echo number_format($summaries['accountTypes']['retirementTotal'], 2); ?></p>
            <p class="text-sm text-gray-600 mt-2">Includes 403b, 401a, Roth IRA, and Traditional IRA</p>
        </div>
        <div class="bg-purple-200 p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-2">Business Accounts</h2>
            <p class="text-2xl">$<?php echo number_format($summaries['accountTypes']['businessTotal'], 2); ?></p>
            <p class="text-sm text-gray-600 mt-2">Business Taxable Brokerage</p>
        </div>
        <div class="bg-purple-200 p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-2">College Savings</h2>
            <p class="text-2xl">$<?php echo number_format($summaries['accountTypes']['collegeTotal'], 2); ?></p>
            <p class="text-sm text-gray-600 mt-2">529 College Fund</p>
        </div>
        <div class="bg-purple-200 p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-2">Personal Brokerage</h2>
            <p class="text-2xl">$<?php echo number_format($summaries['accountTypes']['brokerageTotal'], 2); ?></p>
            <p class="text-sm text-gray-600 mt-2">Personal Taxable Brokerage</p>
        </div>
    </div>

    <!-- Asset Classes Section -->
    <h1 class="text-2xl font-bold mb-4 text-center text-purple-50">Contributions by Asset Class</h1>
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-purple-200 p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-2">Mutual Funds</h2>
            <p class="text-2xl">$<?php echo number_format($summaries['assetClasses']['mutualFundTotal'], 2); ?></p>
        </div>
        <div class="bg-purple-200 p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-2">Equities</h2>
            <p class="text-2xl">$<?php echo number_format($summaries['assetClasses']['equitiesTotal'], 2); ?></p>
        </div>
        <div class="bg-purple-200 p-4 rounded shadow">
            <h2 class="text-lg font-semibold mb-2">Crypto</h2>
            <p class="text-2xl">$<?php echo number_format($summaries['assetClasses']['cryptoTotal'], 2); ?></p>
        </div>
    </div>
    </div>
<?php require_once '../includes/footer.php'; ?>