import '../styles/main.scss'
import Sortable from 'sortablejs'
import axios from 'axios'
import qs from 'qs'

let items         = document.querySelector('.redirects')
let testarea      = document.getElementById('test-area')
let testbutton    = document.getElementById('test-button')
let testinput     = document.getElementById('test-input')
let testresult    = document.getElementById('test-result')
let deletetoggle  = document.getElementById('delete-toggle')
let queryfield    = document.getElementById('query-field')
let querybutton   = document.getElementById('query-button')
let queryresults  = document.getElementById('query-results')
let tabs          = document.getElementById('tabs')
let editor

if (items) {
  Sortable.create(items, {
    filter: '.redirect-header',
    handle: '.redirect-handle',
    onUpdate: () => reweight()
  });
}

tabs.addEventListener('click', function (e) {
  let target = e.target.getAttribute('data-target')
  let el = document.getElementById(target)
  let contents = document.querySelectorAll('.tab-target')
  let alltabs = tabs.querySelectorAll('[data-target]')
  for (let tab of alltabs) {
    tab.classList.remove('selected')
  }
  e.target.classList.add('selected')
  for (let content of contents) {
    content.classList.remove('target-selected')
  }
  el.classList.add('target-selected')
})

if (testbutton) {
  testbutton.addEventListener('click', function (e) {
    e.preventDefault();
    runtest()
  })
}

if (testinput) {
  testinput.addEventListener('keypress', function (e) {
    if (e.which == 13) {
      runtest()
    }
  })
}

if (deletetoggle) {
  deletetoggle.addEventListener('click', function (e) {
    e.preventDefault()
    toggleDeleteMode()
  })
}

if (items) {
  items.addEventListener('click', function(e) {
    if (e.target.classList.contains('redirect-edit') || e.target.parentElement.classList.contains('redirect-edit')) {
      let row = e.target.closest('.redirect-item')
      if (items.classList.contains('delete-mode')) {
        destroy(row)
      } else {
        toggleEdit(row)
      }
    }
  })
}

if (querybutton) {
  querybutton.addEventListener('click', function (e) {
    runQuery(queryfield.value)
  });
}

/**
 * Toggle edit mode on a row
 */
function toggleEdit(row) {
  let patternWrap = row.querySelector('[data-pattern]')
  let replacementWrap = row.querySelector('[data-replacement]')
  if (!row.classList.contains('is-editing')) {
    row.classList.toggle('is-editing')
    patternWrap.innerHTML = '<input type="text" value="' + patternWrap.getAttribute('data-pattern') + '" name="pattern">'
    replacementWrap.innerHTML = '<input type="text" value="' + replacementWrap.getAttribute('data-replacement') + '" name="replacement">'
    row.querySelector('.redirect-edit').innerHTML = '×'
    editor = row.addEventListener('keypress', e => {
      if (e.which === 13) {
        update(row)
      }
    })
  } else {
    row.classList.toggle('is-editing')
    row.removeEventListener('keypress', editor)
    patternWrap.innerHTML = patternWrap.getAttribute('data-pattern')
    replacementWrap.innerHTML = replacementWrap.getAttribute('data-replacement')
    row.querySelector('.redirect-edit').innerHTML = '✎'
  }
}

function toggleDeleteMode() {
  items.classList.toggle('delete-mode')
  let buttons = items.querySelectorAll('.redirect-item .redirect-edit')
  if (items.classList.contains('delete-mode')) {
    for (let button of buttons) {
      button.innerHTML = '<span class="delete">Delete</span>'
    }
    deletetoggle.innerHTML = '<span class="">Edit A Redirect</span>'
  } else {
    for (let button of buttons) {
      button.innerHTML = '✎'
    }
    deletetoggle.innerHTML = '<span class="delete">Delete A Redirect</span>'
  }
}

/**
 * Destroy a particular record.
 */
function destroy(row) {
  let id = row.getAttribute('data-id')
  axios.post('/admin/delete', qs.stringify({
    id: id
  }))
  .then(() => {
    row.remove()
  })
  .catch(err => alert('Unable to delete redirect'))
}

/**
 * Update a given row on the backend.
 */
function update(row) {
  let pattern = row.querySelector('[name="pattern"]').value
  let replacement = row.querySelector('[name="replacement"]').value
  let id = row.getAttribute('data-id')
  axios.post('/admin/update', qs.stringify({
    id: id,
    pattern: pattern,
    replacement: replacement
  }))
  .then(() => {
    row.querySelector('[data-pattern]').setAttribute('data-pattern', pattern)
    row.querySelector('[data-replacement]').setAttribute('data-replacement', replacement)
    toggleEdit(row)
    clearresults()
  })
  .catch(err => alert('Unable to save pattern'))
}

/**
 * Weight the list
 */
function reweight() {
  let all = document.querySelectorAll('.redirect-item[data-id]');
  let indexes = []
  clearresults()
  for (let item of all) {
    indexes.push(item.getAttribute('data-id'))
  }
  axios.post('/admin/weights', qs.stringify({
    weights: indexes
  }))
  .catch(err => alert('Error saving new weights'))
}

/**
 * Clear the results of the search.
 */
function clearresults() {
  let elements = document.querySelectorAll('.redirect-item');
  testresult.innerHTML = ''
  for (let element of elements) {
    element.classList.remove('pattern-matched')
  }
}

/**
 * Run a pattern test.
 */
function runtest() {
  let input = testinput.value
  let elements = document.querySelectorAll('.redirect-item');
  clearresults()
  for (let element of elements) {
    let pattern = new RegExp(element.querySelector('[data-pattern]').getAttribute('data-pattern'), 'i')
    if (pattern.test(input)) {
      let replacement = element.querySelector('[data-replacement]').getAttribute('data-replacement')
      element.classList.add('pattern-matched')
      testresult.innerHTML = '<span class="success">URL will be redirected to: <a href="' + input.replace(pattern, replacement) + '" target="_blank">' + input.replace(pattern, replacement) + '</a></span>'
      return;
    }
  }
  testresult.innerHTML = '<span class="error">No matching pattern. A 404 will be returned.</span>'
}


function runQuery(query) {
  axios.get('/admin/query', {
    params: {
      q: query
    }
  }).then(res => {
    let data = res.data
    if (data[0]) {
      logTable(data)
    } else {
      queryresults.innerHTML = 'No query results'
    }
  })
  .catch(err => queryresults.innerHTML = err.message)
}

function logTable(data) {
  let columns = Object.keys(data[0])
  let table = `<table><tr>`
  for (let column of columns) {
    table += `<th>${column}</th>`
  }
  for (let idx in data) {
    table += `<tr>`
    for (let cell in data[idx]) {
      table += `<td>${data[idx][cell]}</td>`
    }
    table += `</tr>`
  }

  table += `</table>`

  queryresults.innerHTML = table
}
