require('./bootstrap');

const AVAILABLE_UNITS = [
    'X', 'O'
];

const app = new Vue({
    el: '#app',
    data: {
        showUnitSelector: true,
        showBoard: false,
        playerUnit: null,
        board: [
            ['', '', ''],
            ['', '', ''],
            ['', '', '']
        ]
    },
    methods: {
        chooseUnit: function (unit) {
            if (!AVAILABLE_UNITS.includes(unit)) {
                alert('Selected unit ' + unit + ' is unavailable');

                return false;
            }

            this.playerUnit = unit;
            this.showUnitSelector = false;
            this.showBoard = true;
        },
        makeMove: function (row, cell) {
            const newRow = this.board[row].slice(0);
            newRow[cell] = this.playerUnit;
            this.$set(this.board, row, newRow);

            axios.post('/api/make-move', {
                board: this.board,
                playerUnit: this.playerUnit
            }).then(function (response) {
                console.log(response.data);
            });
        },
        restartGame: function () {
            this.showBoard = false;
            this.showUnitSelector = true;
            this.board = [
                ['', '', ''],
                ['', '', ''],
                ['', '', '']
            ];
        }
    }
});
