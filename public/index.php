<?php
require_once '../config/create_db.php';
require_once '../includes/header.php';
?>

<div class="max-w-screen-lg mx-auto">
    <div class="bg-amber-50 max-w-lg rounded-xl mx-auto">
<h1 class="text-center text-xl rounded-t-xl border-b border-black">Add new contribution</h1>
<div class=" mx-auto p-6 bg-amber-50 rounded-b-xl">
    <form action="index.php" method="post" class="grid gap-6">
        <div class="grid grid-cols-2 items-center gap-4">
            <!-- Date -->
            <div class="flex flex-col space-y-2">
                <label for="date" class="text-left">Date:</label>
                <input type="date" id="date" class="p-2 border rounded"/>
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
                <input type="number" id="amount" class="p-2 border rounded"/>
            </div>

        <button type="submit" class="mx-auto bg-purple-400 w-full text-black py-2 px-4 rounded hover:bg-purple-500">
            Submit
        </button>
    </form>
</div>
    </div>

<table class="border-2 border-black w-full bg-amber-50 text-black rounded">
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
    <tr>
        <td>10/30/2024</td>
        <td>TIAA</td>
        <td>Retirement - 403b</td>
        <td>Mutual Fund</td>
        <td>$100</td>
    </tr>
    <tr>
        <td>10/30/2024</td>
        <td>TIAA</td>
        <td>Retirement - 403b</td>
        <td>Mutual Fund</td>
        <td>$100</td>
    </tr>
    <tr>
        <td>10/30/2024</td>
        <td>TIAA</td>
        <td>Retirement - 401a</td>
        <td>Mutual Fund</td>
        <td>$100</td>
    </tr>
    <tr>
        <td>10/30/2024</td>
        <td>Robinhood</td>
        <td>Taxable Brokerage</td>
        <td>Equities</td>
        <td>$100</td>
    </tr>
    <tr>
        <td>10/30/2024</td>
        <td>Robinhood</td>
        <td>Taxable Brokerage</td>
        <td>Crypto</td>
        <td>$100</td>
    </tr>
    <tr>
        <td>10/30/2024</td>
        <td>Robinhood</td>
        <td>Retirement - Roth IRA</td>
        <td>Equities</td>
        <td>$100</td>
    </tr>
    <tr>
        <td>10/30/2024</td>
        <td>Vanguard</td>
        <td>529 College Fund</td>
        <td>Mutual Fund</td>
        <td>$100</td>
    </tr>
    <tr>
        <td>10/30/2024</td>
        <td>Fidelity</td>
        <td>Business - Taxable Brokerage</td>
        <td>Equities</td>
        <td>$100</td>
    </tr>
    </tbody>
    <tfoot class="border-2 border-black">
        <tr>
        <td colspan="4">Total Contributions</td>
        <td>$500</td>
        </tr>
    </tfoot>
</table>
</div>

<?php require_once '../includes/footer.php'; ?>
