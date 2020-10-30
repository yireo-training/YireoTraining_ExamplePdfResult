<?php declare(strict_types=1);

namespace YireoTraining\ExamplePdfResult\Controller\Index;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\FileSystemException;
use YireoTraining\ExamplePdfResult\Result\Pdf as ResultPdf;

class Demo implements ActionInterface
{
    /**
     * @var ResultPdf
     */
    private $resultPdf;

    /**
     * @var ComponentRegistrar
     */
    private $componentRegistrar;
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * Index constructor.
     * @param ResultPdf $resultPdf
     * @param ComponentRegistrar $componentRegistrar
     * @param RequestInterface $request
     */
    public function __construct(
        ResultPdf $resultPdf,
        ComponentRegistrar $componentRegistrar,
        RequestInterface $request
    ) {
        $this->resultPdf = $resultPdf;
        $this->componentRegistrar = $componentRegistrar;
        $this->request = $request;
    }

    /**
     * @return ResultInterface
     * @throws FileSystemException
     */
    public function execute(): ResultInterface
    {
        $contentDisposition = $this->request->getParam('content-disposition', 'attachment');
        $demoPdfFile = $this->getDemoPdfFile();
        return $this->resultPdf
            ->setContentDisposition($contentDisposition)
            ->setFileName($demoPdfFile);
    }

    /**
     * @return string
     */
    private function getDemoPdfFile(): string
    {
        $modulePath = $this->componentRegistrar->getPath('module', 'YireoTraining_ExamplePdfResult');
        return $modulePath . '/media/examples/hello_world.pdf';
    }
}
