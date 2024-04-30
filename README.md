# MotorLeads-PricingWeb

MotorLeads-PricingWeb is a versatile web-based tool designed to provide accurate pricing estimates for vehicles based on user-selected options. This repository houses the codebase for the Motorleads pricing  webpage, allowing users to configure and customize their desired vehicle specifications and receive instant pricing information. With MotorLeads-PricingWeb, users can conveniently explore various vehicle configurations and make informed decisions about their automotive purchases. This project represents a collaboration with Motorleads, a prominent multinational MarTech leader within the automotive industry.

## Table of Contents

1. [Features](#features)
2. [Tech Stack](#tech-stack)
3. [Getting Started](#getting-started)
4. [API Reference](#api-reference)
5. [Demo](#demo)
6. [Authors](#authors)
7. [Licence](#licence)
## Features

- User Authentication: Users are required to log in to access the service and its features.

- Filter Selection: Users can filter cars by brand, model, year, version, mileage, and color.

- Search Results: After the search, users receive a quotation with the following values:

    - Historic Data: Provides historical pricing data, including purchase price, sale price, and average price for the selected vehicle over the past three months.

    - Price Variations: Displays variations in sale price, purchase price, and average price compared to previous months, along with percentage variations.

    - Mileage Statistics: Shows minimum, maximum, and average mileage for the selected vehicle.

- Interactive Table: Includes a dynamic table where users can choose the number of months to visualize sale price, purchase price, and average price values.
## Tech Stack

**Frontend:** HTML, CSS, JavaScript.

**Backend:** PHP.

**Web Server:** Apache (utilizing XAMPP).

**API Integration:** Motorleads API.

## Getting Started

### Dependencies
- **XAMPP:** You will need XAMPP to provide the local server environment.

- **Motorleads API:** Integration with the Motorleads API is required to fetch automobile pricing data.

- **Chart.js:** The Chart.js library is used for visualizing data and creating dynamic charts. For further information on how to use this library, please refer to [Chart.js Guide](https://www.w3schools.com/ai/ai_chartjs.asp).
- **Plotly.js:** Plotly.js is added for additional charting capabilities. It offers interactive, web-based visualizations. Check out the [Plotly.js Documentation](https://plotly.com/javascript/getting-started/) for usage instructions and examples.

### Installing

1. Clone the repository to your local machine.

```
git clone https://github.com/D4nyArt/web-motorleads.git
```

2. Install XAMPP. For detailed instructions on how to install XAMPP on Windows, visit [XAMPP installation guide](https://www.geeksforgeeks.org/how-to-install-xampp-on-windows/).

3. Access the XAMPP control panel and navigate to the **htdocs** directory.
    - Access the XAMPP control panel by opening the XAMPP application.
      
    - Once opened, click on the "Explorer" button. This will open the file explorer at the location where XAMPP is installed. 

      <img src="https://github.com/D4nyArt/web-motorleads/assets/115831908/06d063fb-c145-4a6a-a710-e67f2a4818f0" width="600" height="400">

    - Navigate to the htdocs directory within the XAMPP installation directory. 

      <img src="https://github.com/D4nyArt/web-motorleads/assets/115831908/918ed481-da36-4a11-b7b1-e81cd5da8ec6" width="600" height="400">

4. Place the folder named "MotorLeads" located in the cloned repository folder into the htdocs directory. **It is imperative to name the folder "MotorLeads".**
<img src="https://github.com/D4nyArt/web-motorleads/assets/115831908/d2f26c29-1f94-4387-9356-18e204a4745f" width="600" height="400">

### Executing Program
1. Start the XAMPP control panel and ensure that Apache services are running.
2. Open your web browser and navigate to the project directory, specifically _http://localhost/MotorLeads_.

## API Reference

#### Makes (Select a brand)
- Retrieves a list of available car brands.
```
  GET https://motorleads-api-d3e1b9991ce6.herokuapp.com/api/v1/makes
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `none`    | `none`   |         None               |

- **API Response Example**

```json
[
    {
        "id": "10386c62-fe1a-4d59-b021-fff6f9aba43b",
        "name": "Nissan",
        "year_id": null
    },
    {
        "id": "1dc2f76f-9d0a-4c52-953f-cb278e0753bb",
        "name": "Chevrolet",
        "year_id": null
    },
    {
        "id": "3dcbfb1d-4316-47ac-a64d-0d885808708c",
        "name": "Volkswagen",
        "year_id": null
    },
    {
        "id": "445af99b-c53f-48b1-8133-83d0dff9c768",
        "name": "Toyota",
        "year_id": null
    },
    {
        "id": "other",
        "name": "Otro",
        "year_id": null
    }
]
```

#### Models (Select a model)
- Retrieves a list of models for a specific car brand.

```
  GET https://motorleads-api-d3e1b9991ce6.herokuapp.com/api/v1/makes/<make_id>/models
```

| Parameter | Type     | Description                                       |
| :-------- | :------- | :------------------------------------------------ |
| `make_id` | `string` | **Required**. Id of the brand to fetch models for.|

- **API Response Example for Nissan**

```json
[
    {
        "id": "413d65aa-436e-4bc5-859a-f121d3a65892",
        "name": "Versa"
    },
    {
        "id": "552c1042-5b28-4476-9be2-5f52c92875d8",
        "name": "March"
    },
    {
        "id": "9fa4399b-e57e-4b8d-8cef-aa6762539bd5",
        "name": "Sentra"
    },
    {
        "id": "3b61a4d3-3083-443e-9c80-1a3d284cefb2",
        "name": "Kicks"
    },
    {
        "id": "other",
        "name": "Otro"
    }
]

```
  
#### Years (Select a year)

- Retrieves a list of years for a specific car model.

```
  GET https://motorleads-api-d3e1b9991ce6.herokuapp.com/api/v1/models/<model_id>/years
```
| Parameter | Type     | Description                                        |
| :-------- | :------- | :--------------------------------------------------|
| `model_id`| `string` | **Required**.  Id of the model to fetch years for. |

- **API Response Example for Nissan | March**

```json
[
    {
        "id": "289aee9b-a5ff-4d85-9c1b-407612d3bced",
        "name": "2024"
    },
    {
        "id": "99085bf8-3cf2-4610-8e94-a8cb079a2d44",
        "name": "2023"
    },
    {
        "id": "f16994c4-a5fc-4edd-83e2-f7af3f2bcdff",
        "name": "2022"
    },
    {
        "id": "87257cfc-3097-499c-9cf5-7cdf9c8ac7e0",
        "name": "2021"
    },
    {
        "id": "other",
        "name": "Otro"
    }
]

```
#### Versions (Select a version)

- Retrieves a list of versions for a specific car model and year.

```
  GET https://motorleads-api-d3e1b9991ce6.herokuapp.com/api/v1/models/<model_id>/years/<year_id>/vehicles
```
| Parameter       | Type     | Description                                          |
| :-------------  | :------- | :--------------------------------------------------- |
| `model_id`      | `string` | **Required**. Id of the model to fetch versions for. |
| `year_id`       | `string` | **Required**.  Id of the year to fetch versions for. |

- **API Response Example for Nissan | March | 2024**

```json
[
    {
        "id": "03e4c446-4a93-48e5-ac5c-6632efa59cdd",
        "version": "5 Pts. HB, Advance, L4, 1.6 lt. 106 HP, TM 5, a/ac., VE, pantalla 6.75\", RA-15"
    },
    {
        "id": "9cb54d53-24fa-48f8-b179-1a34a64966d9",
        "version": "5 Pts. HB, Sense, L4, 1.6 lt. 106 HP, TM 5, a/ac., mp3, R-14"
    },
    {
        "id": "5dc631c9-4a54-4ecf-86ea-4680daa43444",
        "version": "5 Pts. HB, Sense, L4, 1.6 lt. 106 HP, TA 4, a/ac., mp3, R-14"
    },
    {
        "id": "e6a03c6e-c7a5-43b9-8e5a-b90455510cca",
        "version": "5 Pts. HB, Advance, L4, 1.6 lt. 106 HP, TA 4, a/ac., VE, pantalla 6.75\", RA-15"
    },
    {
        "id": "dcfd0165-29dd-4914-9535-385d51423cf9",
        "version": "5 Pts. HB, Exclusive Bitono, L4, 1.6 lt. 106 HP, TA 4, a/ac., VE, pantalla 6.75\", RA-16"
    }
]

```

#### Data for Visualization

- Retrieves pricing data for a specific vehicle to be used for visualization, filtered by a specified number of months.

```
  GET /api/v1/vehicles/:vehicle_id/pricings?filter[since]=3
```
| Parameter         | Type     | Description                                                            |
| :---------------- | :------- | :--------------------------------------------------------------------- |
| `vehicle_id`      | `string` | **Required**. . Id of the vehicle version to fetch data for.           |
| `filter[since]`   | `number` | **Required**. . Specifies the number of months for filtering the data. |

- **API Response Example for Nissan | March | 2024 | 5 Pts. HB, Sense, L4, 1.6 lt. 106 HP, TM 5, a/ac., mp3, R-14"**

```json
{
    "vehicle_id": "9cb54d53-24fa-48f8-b179-1a34a64966d9",
    "vehicle_version": "5 Pts. HB, Sense, L4, 1.6 lt. 106 HP, TM 5, a/ac., mp3, R-14",
    "model": "March",
    "make": "Nissan",
    "historic": [
        {
            "year": "2024",
            "month": "4",
            "month_name": "April",
            "purchase_price": "192600.0",
            "sale_price": "224600.0",
            "medium_price": "208600.0"
        },
        {
            "year": "2024",
            "month": "3",
            "month_name": "March",
            "purchase_price": "194600.0",
            "sale_price": "226800.0",
            "medium_price": "210700.0"
        },
        {
            "year": "2024",
            "month": "2",
            "month_name": "February",
            "purchase_price": "195700.0",
            "sale_price": "228100.0",
            "medium_price": "211900.0"
        }
    ],
    "since": 3,
    "sale_price_variation": "-3500.0",
    "sale_price_percentage_variation": "-1.03",
    "purchase_price_variation": "-3100.0",
    "purchase_price_percentage_variation": "-1.07",
    "medium_price_variation": "-3300.0",
    "medium_price_percentage_variation": "-1.05",
    "km_minimum": 833,
    "km_maximum": 1250,
    "km_average": 1041
}

```

## Demo

### Sign in

Before getting started, sign in with your credentials on our platform. Please note that full credential verification functionality is not yet available. Currently, only basic validation is in place, ensuring that the email matches the regex pattern and the password is at least four characters long.

![login_demo](https://github.com/D4nyArt/web-motorleads/assets/115831908/05db5506-e2c1-462d-a066-2e3345970a70)

### Filter Selection
Once logged in, you'll be directed to the filter selection page. Here you can filter cars by the following criteria:

- Brand: Select the brand of the car you're interested in.
- Model: Choose the specific model of the car.
- Year Version: Select the year version of the car.
- Mileage: Define the mileage range of the car.
- Color: Pick the desired color of the car.

![form1_demo](https://github.com/D4nyArt/web-motorleads/assets/115831908/b9cbcb77-5bc1-43ed-8ea9-b315049bd5e2)

![form2_demo](https://github.com/D4nyArt/web-motorleads/assets/115831908/b145e808-06b6-4e72-925f-d8efb77ab7e1)

### Quotation Information
Once you've selected your filters, click the "Search" button to view the quotation information of the selected car. Detailed information will be displayed, including the following data:

- Purchase Value: Estimated purchase price of the car.
- Average Value: Estimated average price of the car.
- Selling Value: Estimated selling price of the car.
- Price Variations: A graph displaying the variations of purchase, average, and selling prices over the last 3 months. You also have the option to change the view to see more months if desired.

Additionally, you'll see a bullet chart representing the car's mileage information:

**Bullet Chart:**
- The blue area represents the mileage inputted by the user.
- The light green range represents the expected minimum to average mileage of the selected car.
- The dark green range represents the expected average to maximum mileage of the selected car.
- A red line separates these two ranges for clarity.

![Captura](https://github.com/D4nyArt/web-motorleads/assets/115831908/875149d4-7584-46d0-8c42-b33c7676d6ee)


## Authors

- [Alyson Melissa Sánchez Serratos](https://github.com/Alyxxxxxxx)
- [Miguel Ángel Pérez Ávila](https://github.com/TGMAPA)
- [Daniel Arteaga Mercado](https://github.com/D4nyArt)
- [Valentino Villegas Martínez](https://github.com/valentino-vm)
- [Ángel Rogelio Cruz Ibarra](https://www.github.com/)
- [Rodrigo Antonio Benítez De La Portilla](https://github.com/Rodrig0at)

## Licence

[MIT](https://choosealicense.com/licenses/mit/)









