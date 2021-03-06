<?php
/**
 * @author     Kristof Ringleff
 * @package    Fooman_PrintOrderPdf
 * @copyright  Copyright (c) 2015 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fooman\PrintOrderPdf\Block;

use Fooman\PhpunitBridge\BaseUnitTestCase;

class PaymentInfoBlockTest extends BaseUnitTestCase
{

    private $objectManager;

    private $helper;

    private $pdf;

    private $moduleManager;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->pdf = $this->objectManager->create(
            \Fooman\PrintOrderPdf\Model\Pdf\Order::class
        );

        $this->moduleManager = $this->objectManager->create(\Magento\Framework\Module\Manager::class);
        $this->helper = $this->objectManager->get(\Magento\Payment\Helper\Data::class);
    }

    /**
     * @magentoDataFixture Magento/Sales/_files/order.php
     * @magentoAppArea     adminhtml
     */
    public function testToPdfAdmin()
    {
        $order = $this->prepareOrder();
        $paymentInfo = $this->helper->getInfoBlock($order->getPayment())->setIsSecureMode(true);
        if ($this->moduleManager->isEnabled('Fooman_PdfCustomiser')) {
            $paymentInfo->setFoomanThemePath('frontend/Magento/blank');
        }
        $this->assertContains('Check / Money order', $paymentInfo->toPdf());
    }

    /**
     * @magentoDataFixture Magento/Sales/_files/order.php
     * @magentoAppArea     frontend
     */
    public function testToPdfFrontend()
    {
        $order = $this->prepareOrder();
        $paymentInfo = $this->helper->getInfoBlock($order->getPayment())->setIsSecureMode(true);
        if ($this->moduleManager->isEnabled('Fooman_PdfCustomiser')) {
            $paymentInfo->setFoomanThemePath('frontend/Magento/blank');
        }
        $this->assertContains('Check / Money order', $paymentInfo->toPdf());
    }

    /**
     * @return mixed
     */
    protected function prepareOrder()
    {
        $order = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            \Magento\Sales\Model\Order::class
        )->loadByIncrementId('100000001');

        return $order;
    }

}
