require('./bootstrap');

const X_UNIT = 'X';
const O_UNIT = 'O';
const DRAW = 'draw';

const AVAILABLE_UNITS = [
    X_UNIT, O_UNIT
];


const app = new Vue({
    el: '#app',
    data: {
        gameId: null,
        showUnitSelector: true,
        showBoard: false,
        playerUnit: null,
        boardState: null,
        winner: null,
        draw: false
    },
    methods: {
        chooseUnit: function (unit) {
            if (!AVAILABLE_UNITS.includes(unit)) {
                alert('Selected unit ' + unit + ' is unavailable');

                return false;
            }

            this.playerUnit = unit;
            this.initGame();

            this.showUnitSelector = false;
            this.showBoard = true;
        },
        makeMove: function (row, cell) {
            this.updateBoard(row, cell, this.playerUnit);

            axios.post(`/api/game/${this.gameId}/move`, {
                x: cell,
                y: row,
                unit: this.playerUnit
            }).then((response) => {
                let terminateStatus = response.data.terminate_status;

                this.boardState = response.data.board_state;

                if (terminateStatus) {
                    switch (terminateStatus) {
                        case DRAW :
                            this.draw = terminateStatus;
                            break;
                        case X_UNIT :
                            this.winner = X_UNIT;
                            break;
                        case O_UNIT :
                            this.winner = O_UNIT;
                            break
                    }
                }
            });
        },
        restartGame: function () {
            this.showBoard = false;
            this.showUnitSelector = true;

            this.draw = null;
            this.winner = null;
        },
        updateBoard: function (row, cell, unit) {
            const newRow = this.boardState[row].slice(0);
            newRow[cell] = unit;
            this.$set(this.boardState, row, newRow);
        },
        initGame: function () {
            return axios.post('/api/init', {
                playerUnit: this.playerUnit
            }).then((response) => {
                this.boardState = response.data.board_state;
                this.gameId = response.data.id
            });
        }
    }
});
