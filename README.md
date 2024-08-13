# Gift Selection Module for PrestaShop

## Description

The Gift Selection Module for PrestaShop allows store administrators to manage promotional gifts that customers can choose during checkout. The module lets administrators assign existing products in the store as gifts and define promotional rules, such as minimum cart total or specific products that trigger the promotion. When a customer selects a gift during checkout, the module automatically deducts the selected gift's stock.

## Features

- Manage promotional gifts directly from the PrestaShop Back Office.
- Assign existing products as promotional gifts.
- Define custom promotional rules (e.g., minimum cart total, specific products in the cart).
- Display available gifts in the shopping cart.
- Automatically deduct stock when a gift is selected.

## Installation

1. **Download or Clone the Repository:**
    - Clone the repository to your PrestaShop `modules/` directory:
      ```bash
      git clone https://github.com/yourusername/giftselection.git giftselection
      ```
    - Or download the ZIP file and extract it to the `modules/` directory in your PrestaShop installation.

2. **Install the Module:**
    - Log in to your PrestaShop Back Office.
    - Navigate to `Modules and Services`.
    - Search for `Gift Selection`.
    - Click `Install`.

3. **Database Setup:**
    - The module will automatically create a table `ps_gift_products` in your database during installation.

## Configuration

1. **Access the Gift Selection Interface:**
    - In the PrestaShop Back Office, navigate to the `Modules` section and find the `Gift Selection` tab.

2. **Add a New Gift:**
    - Click on `Add New` in the Gift Selection interface.
    - Enter the Gift Name, select the Product (from the existing store products), and define the Promotional Rule.
    - Click `Save`.

3. **Promotional Rules:**
    - `total>=100`: Applies the gift if the cart total is greater than or equal to 100.
    - `specific_product=200`: Applies the gift if the product with ID 200 is in the cart.

## Usage

1. **Customer Checkout:**
    - When the customer meets the promotional criteria during checkout, they will see a dropdown list with available gifts.
    - The customer selects a gift, and the module deducts the selected gift's stock automatically.

2. **Stock Management:**
    - The module checks the stock of the selected gift and prevents customers from selecting a gift if it's out of stock.

## Technical Details

- **Hooks Used:**
    - `displayShoppingCart`: To display available gifts in the shopping cart.
    - `actionValidateOrder`: To validate the selected gift and deduct stock when an order is placed.

- **Database Table:**
    - `ps_gift_products`: Stores the gift products and promotional rules.

- **Smarty Template:**
    - `displayShoppingCart.tpl`: Template used to render the gift selection dropdown in the shopping cart.

## Development

- **Languages:** PHP, Smarty
- **Framework:** PrestaShop Module System
- **PrestaShop Version Compatibility:** 1.7+

## Contributing

Contributions are welcome! Please submit a pull request or open an issue to discuss any changes.

## License

This project is licensed under the MIT License. See the `LICENSE` file for more details.

## Author

- **Nicolás Scandizzo** - [github.com/nscando](https://github.com/nscando)
