<div class="menu">
    <a href="account.php" class="menu-item">Account</a>
    <a href="item.php" class="menu-item">Item</a>
    <a href="option.php" class="menu-item">option</a>
    <a href="map.php" class="menu-item">Map</a>
    <a href="optionitem.php" class="menu-item">Option Shop Item</a>
    <a href="player.php" class="menu-item">Player</a>
    <a href="itemshop.php" class="menu-item">Item Shop</a>
    <a href="npc.php" class="menu-item">NPC</a>
    <a href="tabshop.php" class="menu-item">TabShop</a>
    <a href="shop.php" class="menu-item">Shop</a>
    <a href="itemoption.php" class="menu-item">Item Option</a>
</div>

<style>
    .menu {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        padding: 15px;
        background-color: #222;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    }

    .menu-item {
        color: white;
        text-decoration: none;
        padding: 12px 18px;
        background: linear-gradient(135deg, #444, #555);
        border-radius: 8px;
        margin: 5px;
        font-size: 16px;
        font-weight: bold;
        transition: all 0.3s ease-in-out;
        display: inline-block;
    }

    .menu-item:hover {
        background: linear-gradient(135deg, #f2f2f2, #ddd);
        color: #222;
        transform: translateY(-3px);
    }

    @media (max-width: 768px) {
        .menu {
            flex-direction: column;
            align-items: stretch;
        }

        .menu-item {
            width: 90%;
            text-align: center;
        }
    }
</style>