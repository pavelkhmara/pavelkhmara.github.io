const App = {
    data() {
      return {
        placeholderString: 'Add Item Text',
        title: 'My Items List',
        inputValue: '',
        notes: ['note 1', 'note 2']
      }
    },
    methods: {
        addNewNote() {
            if (this.inputValue !== '') {
                this.notes.push(this.inputValue)
                this.inputValue = ''
            }
        },

        editToUpperCase(item) {
          return item.toUpperCase()
        },

        deleteNote(index) {
            if(index === 0) {
                this.notes.shift()
            } else {
                this.notes.splice(index, 1)
            }
        }

    },

    computed: {
        doubleCountComputed() {
            return this.notes.length * 2
        },
    },

    watch: {
        inputValue(value) {
            if(value.length > 10) {
                this.inputValue = ''
            }
        }
    }
}

Vue.createApp(App).mount('#app')
