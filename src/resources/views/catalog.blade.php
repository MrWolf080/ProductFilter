<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог товаров</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background-color: #2c3e50;
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
        }

        header h1 {
            text-align: center;
        }

        .catalog-container {
            display: flex;
            gap: 20px;
        }

        .filters {
            width: 250px;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .filter-group {
            margin-bottom: 20px;
        }

        .filter-group h3 {
            margin-bottom: 10px;
            font-size: 16px;
            color: #2c3e50;
        }

        .filter-option {
            margin-bottom: 5px;
        }

        .filter-option label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .filter-option input {
            margin-right: 8px;
        }

        .products {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .product-card {
            background: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-info {
            padding: 15px;
        }

        .product-title {
            font-size: 18px;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .product-price {
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 10px;
        }

        .product-quantity {
            color: #7f8c8d;
            margin-bottom: 10px;
        }

        .product-properties {
            margin-top: 10px;
            font-size: 14px;
        }

        .property {
            margin-bottom: 3px;
        }

        .property-name {
            font-weight: bold;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            gap: 5px;
        }

        .pagination a, .pagination span {
            display: inline-block;
            padding: 8px 12px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 3px;
            text-decoration: none;
            color: #2c3e50;
        }

        .pagination a:hover {
            background: #f0f0f0;
        }

        .pagination .current {
            background: #2c3e50;
            color: white;
            border-color: #2c3e50;
        }

        .apply-filters {
            background: #2c3e50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 3px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            margin-top: 10px;
        }

        .apply-filters:hover {
            background: #1a252f;
        }

        @media (max-width: 768px) {
            .catalog-container {
                flex-direction: column;
            }

            .filters {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<header>
    <div class="container">
        <h1>Каталог товаров</h1>
    </div>
</header>

<div class="container">
    <form id="filter-form" method="GET" action="#">
        <div class="catalog-container">
            <div class="filters">
                <h2>Фильтры</h2>

                @foreach($availableProperties as $property)
                    <div class="filter-group">
                        <h3>{{ $property->name }}</h3>
                        @foreach($property->values as $value)
                            <div class="filter-option">
                                <label>
                                    <input
                                        type="checkbox"
                                        name="properties[{{ $property->name }}][]"
                                        value="{{ $value }}"
                                        {{ in_array($value, request()->input("properties.{$property->name}", [])) ? 'checked' : '' }}
                                    >
                                    {{ $value }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endforeach

                <button type="submit" class="apply-filters">Применить фильтры</button>
            </div>

            <div class="products">
                @foreach($products as $product)
                    <div class="product-card">
                        <div class="product-info">
                            <h3 class="product-title">{{ $product->name }}</h3>
                            <div class="product-price">{{ number_format($product->price, 2) }} ₽</div>
                            <div class="product-quantity">Остаток: {{ $product->quantity }} шт.</div>

                            <div class="product-properties">
                                @php
                                    // Явно загружаем свойства, если они не загружены
                                    $productProperties = $product->properties ?? $product->properties()->withPivot('value')->get();
                                @endphp

                                @foreach($productProperties as $property)
                                    <div class="property">
                                        <span class="property-name">{{ $property->name }}:</span>
                                        <span>{{ $property->pivot->value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="pagination">
            {{ $products->links() }}
        </div>
    </form>
</div>

<script>
    // Простой JavaScript для мгновенного применения фильтров при изменении чекбоксов
    document.querySelectorAll('.filters input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            document.getElementById('filter-form').submit();
        });
    });
</script>
</body>
</html>
