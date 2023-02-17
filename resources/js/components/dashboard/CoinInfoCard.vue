<template>
    <div>
        <div class="coin-widget">
            <div class="top flex">
                <div class="logo flex">
                    <img :src="coin['icon']" :alt="coin['name']">

                    <div class="coin-text">
                        <p>{{ coin['name'] }}</p>
                        <p class="text-secondary">{{ coin['shortName'] }}</p>
                    </div>
                </div>
                <div class="badges">
                    <span class="badge badge-success">Last price</span>
                </div>
            </div>
            <div class="bottom flex">
                <div class="price">
                    <p>{{ formatter.format(coin['prices']['last']) }}</p>
                </div>

                <div class="indicator">
                    <p>{{ formatter.format(coin['prices']['last'] - coin['prices']['low']) }}</p>
                </div>
            </div>
        </div>


    </div>
</template>

<script>
export default {
    name: 'CoinInfoCard',

    props: {
        coin: {
            type: Object,
            required: true
        },
    },

    data() {
        return {
            formatter: new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
            }),
        }
    }
}
</script>

<style scoped>
.coin-widget {
    min-width: 300px;
    background-color: var(--dark-light);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
    -webkit-box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
    border-radius: 24px;
    height: 189px;
}

.flex {
    display: flex;
}

.coin-widget .top {
    padding: 28px 28px 14px 28px;
    border-bottom: 1px solid #E4E4E41C;
    justify-content: space-between;
}

.coin-widget .top .logo img {
    width: 48px;
    height: 48px;
    margin-right: 12px;
}

.coin-text {
    margin-left: 4px;
}

.coin-text p:nth-child(1) {
    font-weight: 500;
    font-size: 22px;
    margin: 0;
    line-height: 26px;
}

.bottom.flex {
    justify-content: space-between;
    padding: 20px 26px;
}

.badge {
    padding: 6px 8px;
    background: #BCEAFF;
    color: var(--color-primary);
    font-size: 10px;
    border-radius: 14px;
}

.price p {
    font-size: 24px;
    line-height: 1.33333;
    font-weight: 500;
}


.indicator p {
    color: #4FBF67;
    font-weight: 600;
    margin: 0;
    font-size: 13px;
    line-height: 35px;
}
</style>