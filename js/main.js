import { html, render } from 'https://js.imlin.us/component'
import lineChart from 'https://js.imlin.us/line-chart'

import Logotype from './logotype.js'
import Card from './card.js'

function App (props) {
  return () => html`
    ${Logotype()}

    <div class="cards">
      ${props.fuels.map(fuel => Card(props, fuel))}
    </div>
  `
}

async function initializeApp () {
  const fuels = ['95', 'diesel', 'e85']
  const response = await fetch('/api/index.php')
  const prices = await response.json()

  const data = {
    data: prices,
    fuels,
    // TODO: fetch from api instead
    historicPrices: {
      '95': [17.69, 18.89, 19.74, 21.04, 21.84, 23.18, 23.08, 20.78, 19.33, 19.58, 22.04],
      'diesel': [18.92, 21.37, 22.22, 24.47, 25.97, 24.16, 25.86, 24.56, 24.96, 24.61, 28.37],
      'e85': [17.18, 15.84, 17.34, 20.89, 18.29, 19.24, 18.62, 20.37, 18.42, 17.57, 17.82, 16.22]
    }
  }

  render(App(data), document.querySelector('.app'))

  fuels.forEach(fuel => {
    lineChart(data.historicPrices[fuel], `.chart[data-fuel="${fuel}"]`)
  })
}

initializeApp()
