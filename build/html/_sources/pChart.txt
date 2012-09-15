pChart Utility
**************

.. php:class:: pChart

      a PHP class to build charts! http://pchart.sourceforge.net
      released open-source under the GNU Lesser General Public License version 2
      see <http://www.gnu.org/licenses/>.

      :author: Jean-Damien / POGOLOTTI

      :copyright: 2008 Jean-Damien POGOLOTTI

      :version: 1.27d (09/30/08)

      :package: qoob

      :subpackage: utils.pCharts

   .. php:attr:: $Palette

   .. php:attr:: $XSize

   .. php:attr:: $YSize

   .. php:attr:: $Picture

   .. php:attr:: $ImageMap

   .. php:attr:: $ErrorReporting

   .. php:attr:: $ErrorInterface

   .. php:attr:: $Errors

   .. php:attr:: $ErrorFontName

   .. php:attr:: $ErrorFontSize

   .. php:attr:: $GArea_X1

   .. php:attr:: $GArea_Y1

   .. php:attr:: $GArea_X2

   .. php:attr:: $GArea_Y2

   .. php:attr:: $GAreaXOffset

   .. php:attr:: $VMax

   .. php:attr:: $VMin

   .. php:attr:: $VXMax

   .. php:attr:: $VXMin

   .. php:attr:: $Divisions

   .. php:attr:: $XDivisions

   .. php:attr:: $DivisionHeight

   .. php:attr:: $XDivisionHeight

   .. php:attr:: $DivisionCount

   .. php:attr:: $XDivisionCount

   .. php:attr:: $DivisionRatio

   .. php:attr:: $XDivisionRatio

   .. php:attr:: $DivisionWidth

   .. php:attr:: $DataCount

   .. php:attr:: $Currency

   .. php:attr:: $FontName

   .. php:attr:: $FontSize

   .. php:attr:: $DateFormat

   .. php:attr:: $LineWidth

   .. php:attr:: $LineDotSize

   .. php:attr:: $Layers

   .. php:attr:: $AntialiasQuality

   .. php:attr:: $ShadowActive

   .. php:attr:: $ShadowXDistance

   .. php:attr:: $ShadowYDistance

   .. php:attr:: $ShadowRColor

   .. php:attr:: $ShadowGColor

   .. php:attr:: $ShadowBColor

   .. php:attr:: $ShadowAlpha

   .. php:attr:: $ShadowBlur

   .. php:attr:: $BuildMap

   .. php:attr:: $MapFunction

   .. php:attr:: $tmpFolder

   .. php:attr:: $MapID

   .. php:method:: pChart::makepChart()

   .. php:method:: pChart::reportWarnings()

   .. php:method:: pChart::setFontProperties()

   .. php:method:: pChart::setShadowProperties()

   .. php:method:: pChart::clearShadow()

   .. php:method:: pChart::setColorPalette()

   .. php:method:: pChart::createColorGradientPalette()

   .. php:method:: pChart::loadColorPalette()

   .. php:method:: pChart::setLineStyle()

   .. php:method:: pChart::setCurrency()

   .. php:method:: pChart::setGraphArea()

   .. php:method:: pChart::drawGraphArea()

   .. php:method:: pChart::clearScale()

   .. php:method:: pChart::setFixedScale()

   .. php:method:: pChart::drawRightScale()

   .. php:method:: pChart::drawScale()

   .. php:method:: pChart::drawXYScale()

   .. php:method:: pChart::drawGrid()

   .. php:method:: pChart::getLegendBoxSize()

   .. php:method:: pChart::drawLegend()

   .. php:method:: pChart::drawPieLegend()

   .. php:method:: pChart::drawTitle()

   .. php:method:: pChart::drawTextBox()

   .. php:method:: pChart::drawTreshold()

   .. php:method:: pChart::setLabel()

   .. php:method:: pChart::drawPlotGraph()

   .. php:method:: pChart::drawXYPlotGraph()

   .. php:method:: pChart::drawArea()

   .. php:method:: pChart::writeValues()

   .. php:method:: pChart::drawLineGraph()

   .. php:method:: pChart::drawXYGraph()

   .. php:method:: pChart::drawCubicCurve()

   .. php:method:: pChart::drawFilledCubicCurve()

   .. php:method:: pChart::drawFilledLineGraph()

   .. php:method:: pChart::drawOverlayBarGraph()

   .. php:method:: pChart::drawBarGraph()

   .. php:method:: pChart::drawStackedBarGraph()

   .. php:method:: pChart::drawLimitsGraph()

   .. php:method:: pChart::drawRadarAxis()

   .. php:method:: pChart::drawRadar()

   .. php:method:: pChart::drawFilledRadar()

   .. php:method:: pChart::drawBasicPieGraph()

   .. php:method:: pChart::drawFlatPieGraphWithShadow()

   .. php:method:: pChart::drawFlatPieGraph()

   .. php:method:: pChart::drawPieGraph()

   .. php:method:: pChart::drawBackground()

   .. php:method:: pChart::drawGraphAreaGradient()

   .. php:method:: pChart::drawRectangle()

   .. php:method:: pChart::drawFilledRectangle()

   .. php:method:: pChart::drawRoundedRectangle()

   .. php:method:: pChart::drawFilledRoundedRectangle()

   .. php:method:: pChart::drawCircle()

   .. php:method:: pChart::drawFilledCircle()

   .. php:method:: pChart::drawEllipse()

   .. php:method:: pChart::drawFilledEllipse()

   .. php:method:: pChart::drawLine()

   .. php:method:: pChart::drawDottedLine()

   .. php:method:: pChart::drawFromPNG()

   .. php:method:: pChart::drawFromGIF()

   .. php:method:: pChart::drawFromJPG()

   .. php:method:: pChart::drawFromPicture()

   .. php:method:: pChart::drawAlphaPixel()

   .. php:method:: pChart::AllocateColor()

   .. php:method:: pChart::addBorder()

   .. php:method:: pChart::Render()

   .. php:method:: pChart::Stroke()

   .. php:method:: pChart::drawAntialiasPixel()

   .. php:method:: pChart::validateDataDescription()

   .. php:method:: pChart::validateData()

   .. php:method:: pChart::printErrors()

   .. php:method:: pChart::setImageMap()

   .. php:method:: pChart::addToImageMap()

   .. php:method:: pChart::getImageMap()

   .. php:method:: pChart::SaveImageMap()

   .. php:method:: pChart::ToTime()

   .. php:method:: pChart::ToMetric()

   .. php:method:: pChart::ToCurrency()

   .. php:method:: pChart::setDateFormat()

   .. php:method:: pChart::ToDate()

   .. php:method:: pChart::isRealInt()

.. php:function:: RaiseFatal()

.. php:class:: pData

   .. php:attr:: $Data

   .. php:attr:: $DataDescription

   .. php:method:: pData::pData()

   .. php:method:: pData::ImportFromCSV()

   .. php:method:: pData::AddPoint()

   .. php:method:: pData::AddSerie()

   .. php:method:: pData::AddAllSeries()

   .. php:method:: pData::RemoveSerie()

   .. php:method:: pData::SetAbsciseLabelSerie()

   .. php:method:: pData::SetSerieName()

   .. php:method:: pData::SetXAxisName()

   .. php:method:: pData::SetYAxisName()

   .. php:method:: pData::SetXAxisFormat()

   .. php:method:: pData::SetYAxisFormat()

   .. php:method:: pData::SetXAxisUnit()

   .. php:method:: pData::SetYAxisUnit()

   .. php:method:: pData::SetSerieSymbol()

   .. php:method:: pData::removeSerieName()

   .. php:method:: pData::removeAllSeries()

   .. php:method:: pData::GetData()

   .. php:method:: pData::GetDataDescription()